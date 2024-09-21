<?php

namespace App\Http\Controllers\Profile;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index($id)
    {
        $user = User::with('hasOneProfile')->findorfail($id);

        $data = [
            'userID' => $id,
            'user' => $user
        ];

        return view('profile.index', $data);
    }

    public function edit($id)
    {
        $user = User::with('hasOneProfile')->findorfail($id);

        $data = [
            'user' => $user,
            'id' => $id
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
            'role' => 'required'
        ], [
            'nama.required' => 'Nama harus diisi',
            'no_ktp.required' => 'Nomor KTP harus diisi',
            'no_telepon.required' => 'Nomor Telepon harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'agama.required' => 'Agama harus diisi',
            'pendidikan_terakhir.required' => 'Pendidikan Terakhir harus diisi',
            'tempat_lahir.required' => 'Tempat Lahir Terakhir harus diisi',
            'tanggal_lahir.required' => 'Tanggal Lahir Terakhir harus diisi',
            'role.required' => 'role harus diisi'
        ]);

        $user = User::with('hasOneProfile')->where('id', $id)->first();

        if ($request->hasFile('foto_profile')) {
            $fotoProfile = $this->handleFileUpload($request, 'foto_profile', 'foto-profile', $id);
        }

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

        if ($request->nip != null) {
            $request->validate([
                'nip' => 'required|unique:profiles,nip',
            ], [
                'nip.required' => 'NIP harus diisi',
                'nip.unique' => 'NIP sudah dipakai'
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
            'nip' => $request->nip,
            'foto_profile' => $fotoProfile ?? $user->hasOneProfile?->getRawOriginal('foto_profile'),
        ];

        Profile::updateorcreate([
            'user_id' => $id,
        ], $data);

        $dataUser = [
            'username' => $request->username,
            'role' => $request->role
        ];

        if ($request->password) {
            $dataUser['password'] = Hash::make($request->password);
        }

        User::where('id', $id)->update($dataUser);

        return to_route('profile.index', $id)->with('success', 'Berhasil Mengisi Profile');
    }

    function handleFileUpload($request, $fieldName, $folderName, $id)
    {
        // Ambil file dari request
        $file = $request->file($fieldName);

        // Buat nama file unik dengan menambahkan timestamp
        $filename = time() . "." . $file->getClientOriginalExtension();

        // Tentukan folder penyimpanan di dalam folder public
        $folderPath = public_path('file-uploads/' . $folderName);

        // Pastikan folder tujuan ada, jika belum ada, buat folder tersebut
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        // Pindahkan file ke folder tujuan di public
        $file->move($folderPath, $filename);

        return $filename;
    }
}
