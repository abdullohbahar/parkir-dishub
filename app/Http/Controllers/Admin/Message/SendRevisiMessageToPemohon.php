<?php

namespace App\Http\Controllers\Admin\Message;

use App\Models\User;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Models\DokumenPengajuan;
use App\Http\Controllers\Controller;

class SendRevisiMessageToPemohon extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($pengajuanID)
    {
        $pengajuan = Pengajuan::with([
            'hasOnePemohon',
            'hasManyDokumenPengajuan' => function ($query) {
                $query->where('status', 'Revisi');
            }
        ])->where('id', $pengajuanID)->first();

        $user = User::with('hasOneProfile')->where('id', $pengajuan->user_id)->first();
        $nomorHpUser = $user->hasOneProfile?->no_telepon;

        $dokumen = $pengajuan->hasManyDokumenPengajuan->pluck('status')->implode(',');

        if (!$dokumen) {
            return '';
        }

        $namaDokumen = $pengajuan->hasManyDokumenPengajuan->pluck('nama_dokumen')->implode(', ');

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
                'target' => "$nomorHpUser", // nomer hp admin
                'message' => "Anda Perlu Merivisi Dokumen:\n$namaDokumen\nHarap Melakukan Revisi!",
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
