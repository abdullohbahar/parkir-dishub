<?php

namespace App\Http\Controllers\Profile;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $userID = auth()->user()->id;

        $data = [
            'userID' => $userID
        ];

        return view('profile.index', $data);
    }

    public function edit($id)
    {
        $user = User::with('hasOneProfile')->findorfail($id);

        $data = [
            'user' => $user
        ];

        return view('profile.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'no_ktp' => 'required',
            'no_telepon' => 'required',
            'alamat' => 'required',
            'agama' => 'required',
            'pendidikan_terakhir' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
        ], [
            'nama.required' => 'Nama harus diisi',
            'no_ktp.required' => 'Nomor KTP harus diisi',
            'no_telepon.required' => 'Nomor Telepon harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'agama.required' => 'Agama harus diisi',
            'pendidikan_terakhir.required' => 'Pendidikan Terakhir harus diisi',
            'tempat_lahir.required' => 'Tempat Lahir Terakhir harus diisi',
            'tanggal_lahir.required' => 'Tanggal Lahir Terakhir harus diisi',
        ]);

        $user = User::with('hasOneProfile')->where('id', $id)->first();

        if ($user->username != $request->username) {
            $request->validate([
                'username' => 'required|unique:users,username',
            ], [
                'username.required' => 'Username harus diisi',
                'username.unique' => 'Username sudah dipakai'
            ]);
        }

        if ($request->password != null) {
            $request->validate([
                'password' => 'required|confirmed|min:10|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ], [
                'password.required' => 'password harus diisi',
                'password.confirmed' => 'password tidak sama',
                'password.min' => 'password minimal 10 karakter',
                'password.regex' => 'password harus terdiri dari huruf besar, huruf kecil, dan angka',
            ]);
        }

        $data = [
            'user_id' => $id,
            'nama' => $request->nama,
            'no_ktp' => $request->no_ktp,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'agama' => $request->agama,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
        ];

        Profile::updateorcreate([
            'user_id' => $id,
        ], $data);

        $dataUser = [
            'username' => $request->username
        ];

        if ($request->password) {
            $dataUser['password'] = Hash::make($request->password);
        }

        User::where('id', $id)->update($dataUser);

        return to_route('profile.index')->with('success', 'Berhasil Mengisi Profile');
    }
}
