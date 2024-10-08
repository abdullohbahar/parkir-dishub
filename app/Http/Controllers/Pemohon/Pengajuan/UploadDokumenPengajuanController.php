<?php

namespace App\Http\Controllers\Pemohon\Pengajuan;

use App\Models\User;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Models\DokumenPengajuan;
use App\Models\RiwayatPengajuan;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailNotificationController;
use PDF;

class UploadDokumenPengajuanController extends Controller
{
    public function index($pengajuanID)
    {
        $inputDataPermohonanController = new InputDataPermohonanController();

        $redirect = $inputDataPermohonanController->redirectPemohon($pengajuanID);

        if ($redirect && request()->fullUrl() != $redirect) {
            return redirect()->to($redirect);
        }

        $dokumenPengajuan = new DokumenPengajuan();

        $suratPermohonan = $dokumenPengajuan->where('pengajuan_id', $pengajuanID)->where('nama_dokumen', 'Surat Permohonan')->first();
        $ktp = $dokumenPengajuan->where('pengajuan_id', $pengajuanID)->where('nama_dokumen', 'KTP')->first();
        $pasFoto = $dokumenPengajuan->where('pengajuan_id', $pengajuanID)->where('nama_dokumen', 'Pas Foto')->first();
        $denah = $dokumenPengajuan->where('pengajuan_id', $pengajuanID)->where('nama_dokumen', 'Denah')->first();
        $rekom = $dokumenPengajuan->where('pengajuan_id', $pengajuanID)->where('nama_dokumen', 'Rekom')->first();

        $data = [
            'pengajuanID' => $pengajuanID,
            'suratPermohonan' => $suratPermohonan,
            'ktp' => $ktp,
            'pasFoto' => $pasFoto,
            'denah' => $denah,
            'rekom' => $rekom,
        ];

        return view('pemohon.pengajuan.upload-dokumen-permohonan', $data);
    }

    public function uploadDokumen(Request $request, $pengajuanID)
    {
        $userID = auth()->user()->id;

        $file = $request->file('file');
        $filename = time() . " - $request->nama_dokumen ." . $file->getClientOriginalExtension();
        $location = 'file-uploads/Dokumen Pengajuan/'  . $userID .  '/';
        $filepath = $location . $filename;
        $file->storeAs('public/' . $location, $filename, 'public');

        DokumenPengajuan::updateorcreate([
            'pengajuan_id' => $request->pengajuan_id,
            'nama_dokumen' => $request->nama_dokumen,
        ], [
            'file' => $filepath
        ]);

        return redirect()->back()->with('success', 'Berhasil mengunggah');
    }

    public function next($pengajuanID)
    {
        $pengajuan = Pengajuan::find($pengajuanID);

        $pengajuan->update([
            'status' => 'Proses Verifikasi Admin'
        ]);

        $pengajuan->hasOneRiwayatPengajuan()->update([
            'step' => 'Menunggu Verifikasi Admin'
        ]);

        $notification = new EmailNotificationController();

        $users = User::where('role', 'admin')->get();

        foreach ($users as $user) {
            $notification->sendEmail($user->id, 'Pemohon Telah Mengajukan Permohonan Baru, Harap Melakukan Verifikasi Dokumen Permohonan!');
        }

        // $this->sendMessageToAdmin($pengajuanID);

        return to_route('pemohon.wait.verification.dokumen.pengajuan', $pengajuanID)->with('success', 'Harap Menunggu Admin Memverifikasi');
    }

    public function sendMessageToAdmin($pengajuanID)
    {
        $pengajuan = Pengajuan::with('hasOnePemohon')->where('id', $pengajuanID)->first();

        $nomorHpAdmin = User::with('hasOneProfile')
            ->where('role', 'admin')
            ->get()
            ->pluck('hasOneProfile.no_telepon')
            ->implode(',');

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
                'target' => "$nomorHpAdmin", // nomer hp admin
                'message' => "Pemohon Telah Mengajukan Permohonan Baru, Harap Melakukan Verifikasi Dokumen Permohonan!",
                'countryCode' => '62', //optional
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . config('fonnte.fonnte_token') . '' //change TOKEN to your actual token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    }

    public function waitVerification($pengajuanID)
    {
        return view('pemohon.pengajuan.menunggu-verifikasi-dokumen-admin');
    }

    public function templateSuratPermohonan($pengajuanID)
    {
        $pengajuan = Pengajuan::with('hasOnePemohon.hasOneProfile', 'hasOneJenisPengajuan', 'hasOneTipePengajuan')->findorfail($pengajuanID);

        $data = [
            'pengajuan' => $pengajuan
        ];

        $pdf = PDF::loadView('pemohon.pengajuan.template.template-surat-permohonan', $data);

        return $pdf;
    }

    public function streamSuratPermohonan($pengajuanID)
    {
        return $this->templateSuratPermohonan($pengajuanID)->stream('Surat Permohonan.pdf');
    }

    public function downloadSuratPermohonan($pengajuanID)
    {
        return $this->templateSuratPermohonan($pengajuanID)->download('Surat Permohonan.pdf');
    }
}
