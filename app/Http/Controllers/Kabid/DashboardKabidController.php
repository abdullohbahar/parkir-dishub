<?php

namespace App\Http\Controllers\Kabid;

use App\Models\User;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Models\SuratKeputusan;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailNotificationController;

class DashboardKabidController extends Controller
{
    public function index()
    {
        $suratKeputusan = new SuratKeputusan();

        $perluPersetujuan = $suratKeputusan->with('belongsToPengajuan.hasOnePemohon.hasOneProfile', 'belongsToPengajuan.hasOneJenisPengajuan')->where('status', 'Persetujuan Kabid')->get();
        $telahDisetujui = $suratKeputusan->with('belongsToPengajuan.hasOnePemohon.hasOneProfile')->where('status', '==', 'Persetujuan Kadis')->get();

        $data = [
            'perluPersetujuan' => $perluPersetujuan,
            'telahDisetujui' => $telahDisetujui,
        ];

        return view('kabid.dashboard', $data);
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

        return view('kabid.surat-keputusan', $data);
    }

    public function kirimSuratKeputusanKeKadis($pengajuanID)
    {
        SuratKeputusan::updateorcreate([
            'pengajuan_id' => $pengajuanID
        ], [
            'status' => 'Persetujuan Kadis'
        ]);

        // $this->sendMessageToKadis();

        $notification = new EmailNotificationController();

        $users = User::where('role', 'kadis')->get();

        foreach ($users as $user) {
            $notification->sendEmail($user->id, "kabid telah mengirimkan surat keputusan!\nHarap melakukan verifikasi pada surat keputusan tersebut.");
        }

        return to_route('kabid.verifikasi.surat.keputusan', $pengajuanID)->with('success', 'Berhasil menyetujui & mengirim surat keputusan ke Kadis');
    }

    public function sendMessageToKadis()
    {
        $kadis = User::with('hasOneProfile')->where('role', 'kadis')->first();

        $noHpKadis = $kadis->hasOneProfile->no_telepon;

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
                'target' => "$noHpKadis", // nomer hp admin
                'message' => "kabid telah mengirimkan surat keputusan!\nHarap melakukan verifikasi pada surat keputusan tersebut.",
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
