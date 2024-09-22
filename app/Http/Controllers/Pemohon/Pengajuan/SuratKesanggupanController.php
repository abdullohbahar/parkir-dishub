<?php

namespace App\Http\Controllers\Pemohon\Pengajuan;

use PDF;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Models\RiwayatPengajuan;
use App\Models\SuratKesanggupan;
use App\Models\RiwayatVerifikasi;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailNotificationController;

class SuratKesanggupanController extends Controller
{
    public function index($pengajuanID)
    {
        $inputDataPermohonanController = new InputDataPermohonanController();

        $redirect = $inputDataPermohonanController->redirectPemohon($pengajuanID);

        if ($redirect && request()->fullUrl() != $redirect) {
            return redirect()->to($redirect);
        }

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
        $tanggal = \Carbon\Carbon::parse($pengajuan->hasOneJadwalTinjauan->tanggal)->translatedFormat('d F Y');
        $hari = \Carbon\Carbon::parse($pengajuan->hasOneJadwalTinjauan->tanggal)->translatedFormat('l');
        $tanggalSurat = \Carbon\Carbon::parse($pengajuan->hasOneJadwalTinjauan->created_at)->translatedFormat('d F Y');

        $nama = $pengajuan->hasOnePemohon->hasOneProfile->nama;
        $tanggalLahir = $pengajuan->hasOnePemohon->hasOneProfile->tempat_lahir . ', ' . Carbon::parse($pengajuan->hasOnePemohon->hasOneProfile->tanggal_lahir)->translatedFormat('d F Y');
        $agama = $pengajuan->hasOnePemohon->hasOneProfile->agama;
        $pendidikanTerakhir = $pengajuan->hasOnePemohon->hasOneProfile->pendidikan_terakhir;
        $alamat = $pengajuan->hasOnePemohon->hasOneProfile->alamat;
        $noTelepon = $pengajuan->hasOnePemohon->hasOneProfile->no_telepon;
        $lokasiParkir = $pengajuan->alamat_lokasi_parkir;
        $panjang = $pengajuan->panjang ?? '';
        $luas = $pengajuan->luas ?? '';

        $kadis = User::with('hasOneProfile')->where('role', 'kadis')->first();

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
            'kadis' => $kadis
        ];

        $pdf = PDF::loadView('pemohon.pengajuan.template.template-surat-kesanggupan', $data);

        // return $pdf->stream('Jadwal Tinjauan Lapangan.pdf');
        return $pdf->stream('surat-kesanggupan.pdf');

        return view('template.jadwal-tinjauan', $data);
    }

    public function upload(Request $request, $pengajuanID)
    {
        $userID = auth()->user()->id;

        $file = $request->file('file');
        $filename = time() . "- Surat Kesanggupan." . $file->getClientOriginalExtension();
        $location = 'file-uploads/Surat Kesanggupan/'  . $userID .  '/';
        $filepath = $location . $filename;
        $file->storeAs('public/' . $location, $filename, 'public');

        SuratKesanggupan::updateorcreate([
            'pengajuan_id' => $pengajuanID
        ], [
            'file' => $filepath,
            'deadline' => now()
        ]);

        RiwayatVerifikasi::where('pengajuan_id', $pengajuanID)->update([
            'step' => 'Verifikasi Surat Kesanggupan'
        ]);

        RiwayatPengajuan::where('pengajuan_id', $pengajuanID)->update([
            'step' => 'Menunggu Verifikasi Surat Kesanggupan'
        ]);

        // $this->sendMessageToAdmin($pengajuanID);

        $users = User::where('role', 'admin')->get();
        $notification = new EmailNotificationController();

        foreach ($users as $user) {
            $notification->sendEmail($user->id, "Pemohon Telah Mengunggah Surat Kesanggupan, Harap Melakukan Verifikasi Surat Kesanggupan!\nJika surat kesanggupan tidak diverifikasi lebih dari 3 hari, maka pengajuan akan otomatis gagal");
        }

        return to_route('pemohon.menunggu.verifikasi.surat.kesanggupan', $pengajuanID)->with('success', 'Berhasil mengunggah');
    }

    public function menungguVerifikasi($pengajuanID)
    {
        return view('pemohon.pengajuan.menunggu-verifikasi-surat-kesanggupan');
    }

    public function sendMessageToAdmin($pengajuanID)
    {
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
                'message' => "Pemohon Telah Mengunggah Surat Kesanggupan, Harap Melakukan Verifikasi Surat Kesanggupan!\nJika surat kesanggupan tidak diverifikasi lebih dari 3 hari, maka pengajuan akan otomatis gagal",
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
