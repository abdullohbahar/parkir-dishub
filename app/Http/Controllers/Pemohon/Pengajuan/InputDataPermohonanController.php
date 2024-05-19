<?php

namespace App\Http\Controllers\Pemohon\Pengajuan;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class InputDataPermohonanController extends Controller
{
    public function redirectPemohon($pengajuanID)
    {
        $pengajuan = Pengajuan::with('hasOneRiwayatPengajuan')->findOrFail($pengajuanID);

        $riwayat = $pengajuan->hasOneRiwayatPengajuan->step ?? '';

        if ($riwayat == 'Memilih Pengajuan') {
            $redirect = route('pemohon.pilih.jenis.pengajuan');
            return $redirect;
        } else if ($riwayat == 'Input Data Pengajuan') {
            $redirect = route('pemohon.input.data.permohonan', $pengajuanID);
            return $redirect;
        } else if ($riwayat == 'Upload Dokumen Pengajuan') {
            $redirect = route('pemohon.upload.dokumen.pengajuan', $pengajuanID);
            return $redirect;
        } else if ($riwayat == 'Menunggu Verifikasi Admin') {
            $redirect = route('pemohon.wait.verification.dokumen.pengajuan', $pengajuanID);
            return $redirect;
        } else if ($riwayat == 'Tinjauan Lapangan') {
            $redirect = route('pemohon.jadwal.tinjauan.lapangan', $pengajuanID);
            return $redirect;
        } else if ($riwayat == 'Upload Surat Kesanggupan') {
            $redirect = route('pemohon.create.surat.kesanggupan', $pengajuanID);
            return $redirect;
        } else if ($riwayat == 'Menunggu Verifikasi Surat Kesanggupan') {
            $redirect = route('pemohon.menunggu.verifikasi.surat.kesanggupan', $pengajuanID);
            return $redirect;
        } else if ($riwayat == 'Selesai') {
            $redirect = route('pemohon.pengajuan.permohonan');
            return $redirect;
        }


        return null;
    }

    public function index($pengajuanID)
    {
        $redirect = $this->redirectPemohon($pengajuanID);

        if ($redirect && request()->fullUrl() != $redirect) {
            return redirect()->to($redirect);
        }

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
