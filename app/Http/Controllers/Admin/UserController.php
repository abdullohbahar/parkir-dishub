<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = User::with([
                'hasOneProfile' => function ($query) {
                    $query->orderBy('nama', 'asc');
                }
            ])
                // ->where('role', '!=', 'admin')
                ->get();

            // return $query;
            return Datatables::of($query)
                ->addColumn('nama', function ($item) {
                    return $item->hasOneProfile?->nama;
                })
                ->addColumn('no_telepon', function ($item) {
                    return $item->hasOneProfile?->no_telepon;
                })
                ->addColumn('aksi', function ($item) {
                    $route = route('profile.edit', $item->id);
                    $routeShow = route('profile.index', $item->id);

                    return "
                    <div class='btn-group' role='group' aria-label='Basic example'>
                        <a href='$route' class='btn btn-warning btn-sm'>Edit</a>
                        <a href='$routeShow' class='btn btn-info btn-sm'>Profile</a>
                        <a data-id='$item->id' data-nama='{$item->hasOneProfile?->nama}' id='removeBtn' style='cursor: pointer !important;' class='btn btn-sm btn-danger'><b>Hapus</b></a>
                    </div>
                    ";
                })
                ->rawColumns(['nama', 'no_telepon', 'aksi'])
                ->make();
        }

        return view('admin.user.index');
    }

    public function create()
    {
        $user = new User();

        $kasi = $user->where('role', 'kasi')->exists();
        $kabid = $user->where('role', 'kabid')->exists();
        $kadis = $user->where('role', 'kadis')->exists();

        $data = [
            'kasi' => $kasi,
            'kabid' => $kabid,
            'kadis' => $kadis
        ];

        return view('admin.user.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:10|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'role' => 'required'
        ], [
            'nama.required' => 'nama harus diisi',
            'username.required' => 'username harus diisi',
            'username.unique' => 'username sudah dipakai',
            'email.required' => 'email harus diisi',
            'email.email' => 'email tidak valid',
            'email.unique' => 'email sudah dipakai',
            'password.required' => 'password harus diisi',
            'password.confirmed' => 'password tidak sama',
            'password.min' => 'password minimal 10 karakter',
            'password.regex' => 'password harus terdiri dari huruf besar, huruf kecil, dan angka',
            'role.required' => 'role harus diisi'
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        Profile::create([
            'user_id' => $user->id,
            'nama' => $request->nama,
        ]);

        return to_route('user.index')->with('success', 'Berhasil menambah user');
    }
}
