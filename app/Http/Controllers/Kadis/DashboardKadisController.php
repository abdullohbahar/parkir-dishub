<?php

namespace App\Http\Controllers\Kadis;

use App\Models\User;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Models\SuratKeputusan;
use App\Http\Controllers\Controller;

class DashboardKadisController extends Controller
{
    public function index()
    {
        $suratKeputusan = new SuratKeputusan();

        $perluPersetujuan = $suratKeputusan->with('belongsToPengajuan.hasOnePemohon.hasOneProfile', 'belongsToPengajuan.hasOneJenisPengajuan')->where('status', 'Persetujuan Kadis')->get();
        $telahDisetujui = $suratKeputusan->with('belongsToPengajuan.hasOnePemohon.hasOneProfile')->where('status', '==', 'Selesai')->get();

        $data = [
            'perluPersetujuan' => $perluPersetujuan,
            'telahDisetujui' => $telahDisetujui,
        ];

        return view('kadis.dashboard', $data);
    }

    public function setujui($pengajuanID)
    {
        $pengajuan = Pengajuan::findorfail($pengajuanID);
        $suratKeputusan = SuratKeputusan::where('pengajuan_id', $pengajuanID)->first();

        $data = [
            'pengajuanID' => $pengajuanID,
            'pengajuan' => $pengajuan,
            'suratKeputusan' => $suratKeputusan
        ];

        return view('kadis.surat-keputusan', $data);
    }

    public function kirimSuratKeputusanKeKadis($pengajuanID)
    {
        SuratKeputusan::updateorcreate([
            'pengajuan_id' => $pengajuanID
        ], [
            'status' => 'Selesai'
        ]);

        $this->sendMessageToAll($pengajuanID);

        return to_route('kadis.verifikasi.surat.keputusan', $pengajuanID)->with('success', 'Berhasil Menyetujui Surat Keputusan');
    }

    public function sendMessageToAll($pengajuanID)
    {
        $admin = User::with('hasOneProfile')->where('role', 'admin')->first();

        $noHpAdmin = $admin->hasOneProfile->no_telepon;

        $pengajuan = Pengajuan::with('hasOnePemohon.hasOneProfile')->findorfail($pengajuanID);

        $noHpPemohon = $pengajuan->hasOnePemohon->hasOneProfile->no_telepon;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => "$noHpAdmin,$noHpPemohon", // nomer hp admin
                'message' => "Surat keputusan telah disetujui dan permohonan anda telah disetujui.\nHarap mengunduh surat keputusan pada halaman permohonan!",
                'countryCode' => '62', //optional
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . config('fonnte.fonnte_token') . '' //change TOKEN to your actual token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    }
}
