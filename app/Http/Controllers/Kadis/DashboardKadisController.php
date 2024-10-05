<?php

namespace App\Http\Controllers\Kadis;

use PDF;
use App\Models\User;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Models\SuratKeputusan;
use App\Models\RiwayatPengajuan;
use App\Models\RiwayatVerifikasi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\EmailNotificationController;

class DashboardKadisController extends Controller
{
    public function index()
    {
        $suratKeputusan = new SuratKeputusan();

        $perluPersetujuan = $suratKeputusan->with('belongsToPengajuan.hasOnePemohon.hasOneProfile', 'belongsToPengajuan.hasOneJenisPengajuan')->where('status', 'Persetujuan Kadis')->get();
        $telahDisetujui = SuratKeputusan::with('belongsToPengajuan.hasOnePemohon.hasOneProfile')->where('status', 'Selesai')->get();

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
        $generatePDF = $this->generateSuratKeputusanPDF($pengajuanID);

        SuratKeputusan::updateorcreate([
            'pengajuan_id' => $pengajuanID
        ], [
            'status' => 'Selesai',
            'file' => $generatePDF
        ]);

        Pengajuan::findorfail($pengajuanID)->update([
            'status' => 'Selesai'
        ]);

        RiwayatVerifikasi::where('pengajuan_id', $pengajuanID)->update([
            'step' => 'Selesai'
        ]);

        RiwayatPengajuan::where('pengajuan_id', $pengajuanID)->update([
            'step' => 'Selesai'
        ]);

        // $this->sendMessageToAll($pengajuanID);

        $users = User::where('role', 'admin')->get();
        $notification = new EmailNotificationController();

        foreach ($users as $user) {
            $notification->sendEmail($user->id, "Surat keputusan telah disetujui dan permohonan anda telah disetujui.\nHarap mengunduh surat keputusan pada halaman permohonan!");
        }

        $pengajuan = Pengajuan::with('hasOnePemohon.hasOneProfile')->findorfail($pengajuanID);

        $notification->sendEmail($pengajuan->user_id, "Surat keputusan telah disetujui dan permohonan anda telah disetujui.\nHarap mengunduh surat keputusan pada halaman permohonan!");


        return to_route('kadis.verifikasi.surat.keputusan', $pengajuanID)->with('success', 'Berhasil Menyetujui Surat Keputusan');
    }

    public function generateSuratKeputusanPDF($pengajuanID)
    {
        $logoPath = public_path('img/kab-bantul.png');
        $encodeLogo = base64_encode(file_get_contents($logoPath));

        $aksaraPath = public_path('img/aksara-dishub.png');
        $encodeAksara = base64_encode(file_get_contents($aksaraPath));

        $bsrePath = public_path('img/bsre.jpeg');
        $encodeBsre = base64_encode(file_get_contents($bsrePath));

        $pengajuan = Pengajuan::with('hasOneJadwalTinjauan', 'hasOnePemohon.hasOneProfile')->findOrFail($pengajuanID);

        \Carbon\Carbon::setLocale('id');
        $tanggal = \Carbon\Carbon::parse($pengajuan->hasOneJadwalTinjauan->tanggal)->translatedFormat('L F Y');
        $hari = \Carbon\Carbon::parse($pengajuan->hasOneJadwalTinjauan->tanggal)->translatedFormat('l');
        $tanggalSurat = \Carbon\Carbon::parse($pengajuan->hasOneJadwalTinjauan->created_at)->translatedFormat('L F Y');

        $kepada = [];
        $kepada['pemohon'] = $pengajuan->hasOnePemohon->hasOneProfile->nama;

        $kadis = User::with('hasOneProfile')->where('role', 'kadis')->first();

        // Buat QR Code dalam format PNG
        $qrcode = QrCode::size(100)
            ->format('png')
            ->merge(public_path('img/kab-bantul.png'), 0.5, true) // Ganti dengan path yang benar
            ->errorCorrection('M')
            ->generate(route('preview.surat.keputusan', $pengajuanID));

        // Encode QR Code ke dalam Base64
        $base64 = base64_encode($qrcode);

        // Buat string untuk image tag
        $qrcode = 'data:image/png;base64,' . $base64;

        $data = [
            'aksara' => $encodeAksara,
            'logo' => $encodeLogo,
            'pengajuan' => $pengajuan,
            'tanggal' => $tanggal,
            'hari' => $hari,
            'tanggalSurat' => $tanggalSurat,
            'kepada' => $kepada,
            'kadis' => $kadis,
            'qrcode' => $qrcode,
            'bsre' => $encodeBsre
        ];

        $pdf = PDF::loadView('template.surat-keputusan', $data);

        // Tentukan nama file dan lokasi penyimpanan di folder public
        $fileDir = public_path('file-uploads/pdf/');
        $fileName = 'surat_keputusan_' . $pengajuanID . '.pdf';
        $filePath = $fileDir . $fileName;

        // Periksa apakah folder sudah ada, jika tidak, buat folder
        if (!file_exists($fileDir)) {
            mkdir($fileDir, 0755, true);
        }

        // Simpan file PDF
        $pdf->save($filePath);

        return $this->signTte($filePath, $fileName);
        // return $filePath;
    }

    public function signTte($file, $filename)
    {
        $username = env("TTE_USERNAME");
        $password = env("TTE_PASSWORD");
        $passphrase = env("TTE_PASSPHRASE");
        $url = env("TTE_URL");

        $kadis = User::where('role', 'kadis')->first();

        $data = [
            'nik' => $kadis->hasOneProfile->no_ktp,
            'passphrase' => $passphrase,
            'tampilan' => 'invisible',
            'location' => 'Bantul'
        ];

        $response = Http::withBasicAuth($username, $password)
            ->attach(
                'file',
                file_get_contents($file),
                $filename
            )
            ->post($url, $data);

        // dd($data, $response);

        // Cek apakah response sukses
        if ($response->successful()) {
            // Ambil konten file PDF yang diterima dari API
            $pdfContent = $response->body(); // Mengambil respons binary dari API

            // Tentukan lokasi penyimpanan file PDF di folder public
            $fileDir = public_path('file-uploads/signed-pdf/');
            $signedFileName = 'signed_' . $filename;
            $filePath = $fileDir . $signedFileName;

            // Periksa apakah folder sudah ada, jika tidak, buat folder
            if (!file_exists($fileDir)) {
                mkdir($fileDir, 0755, true);
            }

            // Simpan file PDF ke folder public
            file_put_contents($filePath, $pdfContent);

            // Hapus file asli setelah file baru berhasil disimpan
            if (file_exists($file)) {
                unlink($file); // Menghapus file yang lama (yang dikirim ke API)
            }

            // Return atau lakukan sesuatu setelah file tersimpan, misal return path file
            // return response()->json(['success' => 'PDF signed and saved successfully', 'file_path' => $filePath]);

            return 'file-uploads/signed-pdf/' . $signedFileName;
        } else {
            // Jika request gagal, kembalikan respons error
            return response()->json(['error' => 'Failed to sign PDF', 'status_code' => $response->status()], $response->status());
        }
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
