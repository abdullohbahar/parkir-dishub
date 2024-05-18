<?php

namespace App\Http\Controllers\Pemohon\Pengajuan;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class InputDataPermohonanController extends Controller
{
    public function redirect($pengajuanID)
    {
        $pengajuan = Pengajuan::with('hasOneRiwayatPengajuan')->findOrFail($pengajuanID);

        if ($pengajuan->hasOneRiwayatPengajuan->step == 'Menunggu Verifikasi Admin') {
            return redirect()->route('admin.verifikasi.dokumen', $pengajuanID);
        } else if ($pengajuan->hasOneRiwayatPengajuan->step == 'Tinjauan Lapangan') {
            return redirect()->route('pemohon.jadwal.tinjauan.lapangan', $pengajuanID);
        }
    }

    public function index($pengajuanID)
    {
        $pengajuan = Pengajuan::with('hasOnePemohon.hasOneProfile', 'hasOneJenisPengajuan')->findorfail($pengajuanID);

        $data = [
            'pengajuan' => $pengajuan
        ];

        return view('pemohon.pengajuan.input-data-permohonan', $data);
    }

    public function store(Request $request, $pengajuanID)
    {
        $request->validate([
            'longitude' => 'required',
            'nama_pemilik' => 'required',
            'latitude' => 'required',
            'alamat_lokasi_parkir' => 'required',
            'lokasi_pengelolaan_parkir' => 'required',
        ], [
            'longitude.required' => 'longitude harus diisi',
            'longitude.required' => 'nama pemilik harus diisi',
            'latitiude.required' => 'latitiude harus diisi',
            'lokasi_pengelolaan_parkir.required' => 'lokasi parkir harus diisi',
            'alamat_lokasi_parkir.required' => 'alamat parkir harus diisi',
        ]);

        $pengajuan = Pengajuan::with('hasOneJenisPengajuan')->where('id', $pengajuanID)->first();

        if ($pengajuan->hasOneJenisPengajuan->jenis == 'Tepi Jalan') {
            $request->validate([
                'panjang' => 'required',
            ], [
                'panjang.required' => 'panjang harus diisi',
            ]);
        } else if ($pengajuan->hasOneJenisPengajuan->jenis == 'Khusus Parkir') {
            $request->validate([
                'luas' => 'required',
            ], [
                'luas.required' => 'luas harus diisi',
            ]);
        }

        $pengajuan->update([
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'alamat_lokasi_parkir' => $request->alamat_lokasi_parkir,
            'panjang' => $request->panjang,
            'luas' => $request->luas,
            'nama_pemilik' => $request->nama_pemilik,
            'lokasi_pengelolaan_parkir' => $request->lokasi_pengelolaan_parkir,
        ]);

        $pengajuan->hasOneRiwayatPengajuan()->update([
            'step' => 'Upload Dokumen Pengajuan'
        ]);

        return to_route('pemohon.upload.dokumen.pengajuan', $pengajuanID)->with('success', 'Berhasil');
    }
}
