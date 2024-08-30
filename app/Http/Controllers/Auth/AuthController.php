<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\V3\KeycloakService;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


class AuthController extends Controller
{
    public function index()
    {
        return view('login.index');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'g-recaptcha-response' => 'required|captcha'
        ], [
            'username.required' => ':attribute harus diisi',
            'password.required' => ':attribute harus diisi',
            'g-recaptcha-response.required' => 'Recaptcha harus diisi',
        ]);

        $auth = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($auth)) {
            $request->session()->regenerate();

            switch (Auth::user()->role) {
                case 'pemohon':
                    return redirect()->route('pemohon.dashboard');
                    break;
                case 'admin':
                    return redirect()->route('admin.dashboard');
                    break;
                case 'kasi':
                    return redirect()->route('kasi.dashboard');
                    break;
                case 'kabid':
                    return redirect()->route('kabid.dashboard');
                    break;
                case 'kadis':
                    return redirect()->route('kadis.dashboard');
                    break;
                default:
                    return redirect()->back()->with('error', 'Username atau password salah');
            }
        }

        return redirect()->back()->with([
            'error' => 'username atau password salah',
            'email' => $request->email
        ]);
    }

    public function redirectToKeycloak()
    {
        // Keycloak below v3.2 requires no scopes to be set. 
        // Later versions require the openid scope for all requests.
        // return Socialite::driver('keycloak')->scopes(['openid'])->redirect();
        return Socialite::driver('keycloak')->redirect('http://localhost:8000/callback');
    }

    public function handleKeycloakCallback(Request $request)
    {
        $user = Socialite::driver('keycloak')->user();

        $email = $user->email;

        // cek user exist or not
        $existingUser = User::where('email', $email)->first();

        if ($existingUser == null) {
            User::create([
                'username' => Str::slug($user->name) ?? '-',
                'email' => $user->email,
                'role' => 'pemohon'
            ]);
        }

        $user = User::where('email', $user->email)->first();

        // Otentikasi user tanpa password
        Auth::login($user);

        $request->session()->regenerate();

        return to_route('pemohon.dashboard');
    }

    public function logout()
    {
        $role = auth()->user()->role;

        // Logout of your app.
        Auth::logout();

        if ($role == 'pemohon' || $role == 'pemrakarsa' || $role == 'konsultan') {
            # code...
            // The URL the user is redirected to after logout.
            $redirectUri = env('APP_URL');

            return redirect(Socialite::driver('keycloak')->getLogoutUrl($redirectUri, env('KEYCLOAK_CLIENT_ID')));
        } else {
            return to_route('login');
        }
    }
}
