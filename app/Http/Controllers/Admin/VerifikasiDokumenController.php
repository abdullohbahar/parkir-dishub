<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Models\DokumenPengajuan;
use App\Http\Controllers\Controller;

class VerifikasiDokumenController extends Controller
{
    public function revisi(Request $request)
    {
        DokumenPengajuan::where('id', $request->dokumenID)->update([
            'alasan' => $request->alasan,
            'status' => 'Revisi'
        ]);

        return redirect()->back()->with('success', 'Berhasil');
    }

    public function setujui($dokumenID)
    {
        try {
            DokumenPengajuan::where('id', $dokumenID)->update([
                'is_approved' => true,
                'status' => 'Disetujui'
            ]);

            // Mengembalikan respons JSON sukses dengan status 200
            return response()->json([
                'message' => 'Berhasil Menyetujui Dokumen',
                'code' => 200,
                'error' => false
            ]);
        } catch (\Exception $e) {
            // Menangkap exception jika terjadi kesalahan
            return response()->json([
                'message' => 'Gagal Menyetujui Dokumen' . $e,
                'code' => 500,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function tolak(Request $request)
    {
        $pengajuan = Pengajuan::findorfail($request->pengajuan_id);

        $pengajuan->status = 'Tolak';
        $pengajuan->save();

        foreach ($pengajuan->hasManyDokumenPengajuan as $document) {
            DokumenPengajuan::where('id', $document->id)
                ->update([
                    'alasan' => $request->alasan,
                    'is_approved' => false,
                    'status' => 'Ditolak'
                ]);
        }

        $this->sendRejectMessageToPemohon($request->pengajuan_id);

        return redirect()->back()->with('success', 'Berhasil Menolak');
    }

    public function sendRejectMessageToPemohon($pengajuanID)
    {
        $pengajuan = Pengajuan::with('hasOnePemohon', 'hasOneDokumenPengajuan')->where('id', $pengajuanID)->first();

        $user = User::with('hasOneProfile')->where('id', $pengajuan->user_id)->first();

        $nomorHpUser = $user->hasOneProfile?->no_telepon;

        $alasan = $pengajuan->hasOneDokumenPengajuan->alasan;

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
                'message' => "Admin Menolak Permohonan Anda, Dengan Alasan:\n$alasan\nHarap Melakukan Permohonan Ulang!",
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
