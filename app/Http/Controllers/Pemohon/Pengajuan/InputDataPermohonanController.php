<?php

namespace App\Http\Controllers\Pemohon\Pengajuan;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class InputDataPermohonanController extends Controller
{
    public function index($pengajuanID)
    {
        $pengajuan = Pengajuan::with('hasOnePemohon.hasOneProfile')->findorfail($pengajuanID);

        $data = [
            'pengajuan' => $pengajuan
        ];

        return view('pemohon.pengajuan.input-data-permohonan', $data);
    }

    public function store(Request $request, $pengajuanID)
    {
        $request->validate([
            'longitude' => 'required',
            'latitude' => 'required',
            'alamat_lokasi_parkir' => 'required',
            'panjang' => 'required',
            'luas' => 'required',
        ], [
            'longitude.required' => 'longitude harus diisi',
            'latitiude.required' => 'latitiude harus diisi',
            'alamat_lokasi_parkir.required' => 'alamat lokasi parkir harus diisi',
            'panjang.required' => 'panjang harus diisi',
            'luas.required' => 'luas harus diisi',
        ]);

        $pengajuan = Pengajuan::where('id', $pengajuanID)->first();


        $pengajuan->update([
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'alamat_lokasi_parkir' => $request->alamat_lokasi_parkir,
            'panjang' => $request->panjang,
            'luas' => $request->luas,
        ]);

        $pengajuan->hasOneRiwayatPengajuan()->update([
            'step' => 'Upload Dokumen Pengajuan'
        ]);

        return to_route('pemohon.upload.dokumen.pengajuan', $pengajuanID)->with('success', 'Berhasil');
    }
}
