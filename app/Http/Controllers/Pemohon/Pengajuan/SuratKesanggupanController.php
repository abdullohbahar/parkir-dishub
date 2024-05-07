<?php

namespace App\Http\Controllers\Pemohon\Pengajuan;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use PDF;

class SuratKesanggupanController extends Controller
{
    public function index($pengajuanID)
    {
        $data = [
            'pengajuanID' => $pengajuanID
        ];

        return view('pemohon.pengajuan.surat-kesanggupan', $data);
    }

    public function templateSuratKesanggupan($pengajuanID)
    {
        $logoPath = public_path('img/kab-bantul.png');
        $encodeLogo = base64_encode(file_get_contents($logoPath));

        $aksaraPath = public_path('img/aksara-dishub.png');
        $encodeAksara = base64_encode(file_get_contents($aksaraPath));

        $pengajuan = Pengajuan::with('hasOneJadwalTinjauan', 'hasOnePemohon.hasOneProfile')->findOrFail($pengajuanID);

        \Carbon\Carbon::setLocale('id');
        $tanggal = \Carbon\Carbon::parse($pengajuan->hasOneJadwalTinjauan->tanggal)->translatedFormat('L F Y');
        $hari = \Carbon\Carbon::parse($pengajuan->hasOneJadwalTinjauan->tanggal)->translatedFormat('l');
        $tanggalSurat = \Carbon\Carbon::parse($pengajuan->hasOneJadwalTinjauan->created_at)->translatedFormat('L F Y');

        $nama = $pengajuan->hasOnePemohon->hasOneProfile->nama;
        $tanggalLahir = $pengajuan->hasOnePemohon->hasOneProfile->tempat_lahir . ', ' . Carbon::parse($pengajuan->hasOnePemohon->hasOneProfile->tanggal_lahir)->translatedFormat('L F Y');
        $agama = $pengajuan->hasOnePemohon->hasOneProfile->agama;
        $pendidikanTerakhir = $pengajuan->hasOnePemohon->hasOneProfile->pendidikan_terakhir;
        $alamat = $pengajuan->hasOnePemohon->hasOneProfile->alamat;
        $noTelepon = $pengajuan->hasOnePemohon->hasOneProfile->no_telepon;
        $lokasiParkir = $pengajuan->alamat_lokasi_parkir;
        $panjang = $pengajuan->panjang ?? '';
        $luas = $pengajuan->luas ?? '';

        $data = [
            'aksara' => $encodeAksara,
            'logo' => $encodeLogo,
            'pengajuan' => $pengajuan,
            'tanggal' => $tanggal,
            'hari' => $hari,
            'tanggalSurat' => $tanggalSurat,
            'nama' => $nama,
            'tanggalLahir' => $tanggalLahir,
            'agama' => $agama,
            'alamat' => $alamat,
            'pendidikanTerakhir' => $pendidikanTerakhir,
            'noTelepon' => $noTelepon,
            'lokasiParkir' => $lokasiParkir,
            'luas' => $luas,
            'panjang' => $panjang,
        ];

        $pdf = PDF::loadView('pemohon.pengajuan.template.template-surat-kesanggupan', $data);

        // return $pdf->stream('Jadwal Tinjauan Lapangan.pdf');
        return $pdf->stream('surat-kesanggupan.pdf');

        return view('template.jadwal-tinjauan', $data);
    }

    // next task membuat upload surat kesanggupan
}
