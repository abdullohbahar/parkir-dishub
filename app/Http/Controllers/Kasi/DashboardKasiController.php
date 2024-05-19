<?php

namespace App\Http\Controllers\Kasi;

use App\Models\User;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Models\SuratKeputusan;
use App\Http\Controllers\Controller;

class DashboardKasiController extends Controller
{
    public function index()
    {
        $suratKeputusan = new SuratKeputusan();

        $perluPersetujuan = $suratKeputusan->with('belongsToPengajuan.hasOnePemohon.hasOneProfile', 'belongsToPengajuan.hasOneJenisPengajuan')->where('status', 'Persetujuan Kasi')->get();
        $telahDisetujui = $suratKeputusan->with('belongsToPengajuan.hasOnePemohon.hasOneProfile')->where('status', '!=', 'Persetujuan Kasi')->get();

        $data = [
            'perluPersetujuan' => $perluPersetujuan,
            'telahDisetujui' => $telahDisetujui,
        ];

        return view('kasi.dashboard', $data);
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

        return view('kasi.surat-keputusan', $data);
    }

    public function kirimSuratKeputusanKeKabid($pengajuanID)
    {
        SuratKeputusan::updateorcreate([
            'pengajuan_id' => $pengajuanID
        ], [
            'status' => 'Persetujuan Kabid'
        ]);

        $this->sendMessageToKabid();

        return to_route('kasi.verifikasi.surat.keputusan', $pengajuanID)->with('success', 'Berhasil menyetujui & mengirim surat keputusan ke Kabid');
    }

    public function sendMessageToKabid()
    {
        $kabid = User::with('hasOneProfile')->where('role', 'kabid')->first();

        $noHpKabid = $kabid->hasOneProfile->no_telepon;

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
                'target' => "$noHpKabid", // nomer hp admin
                'message' => "Kasi telah mengirimkan surat keputusan!\nHarap melakukan verifikasi pada surat keputusan tersebut.",
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
