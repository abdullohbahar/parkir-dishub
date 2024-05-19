<?php

namespace App\Http\Controllers\Admin\Message;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SendApprovedToPemohon extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($jadwal)
    {
        $nomorPemohon = $jadwal->belongsToPengajuan->hasOnePemohon->hasOneProfile->no_telepon;
        $namaWebsite = env('APP_URL');

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
                'target' => "$nomorPemohon", // nomer hp pemohon
                'message' => "Admin telah menyetujui permohonan, dan telah membuat jadwal tinjauan lapangan.\nHarap melakukan pengecekan pada website $namaWebsite , untuk mengunduh jadwal tinjauan lapangan!",
                'countryCode' => '62', //optional
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: 2Ap5o4gaEsJrHmNuhLDH' //change TOKEN to your actual token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    }
}
