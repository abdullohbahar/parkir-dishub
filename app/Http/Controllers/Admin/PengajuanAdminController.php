<?php

namespace App\Http\Controllers\Admin;

use PDF;
use DataTables;
use App\Models\User;
use App\Models\Pengajuan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SuratKeputusan;
use App\Models\RiwayatPengajuan;
use App\Models\SuratKesanggupan;
use App\Models\RiwayatVerifikasi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\JadwalTinjauanLapangan;
use App\Http\Controllers\EmailNotificationController;
use App\Http\Controllers\Admin\Message\SendApprovedToPemohon;
use App\Http\Controllers\Admin\Message\SendRevisiMessageToPemohon;

class PengajuanAdminController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $userID = auth()->user()->id;

            $query = Pengajuan::with('hasOnePemohon', 'hasOneJenisPengajuan', 'hasOneTipePengajuan')
                ->orderBy('updated_at', 'desc')
                ->get();

            // return $query;
            return Datatables::of($query)
                ->addColumn('pemohon', function ($item) {
                    return $item->hasOnePemohon->hasOneProfile->nama;
                })
                ->addColumn('jenis', function ($item) {
                    return $item->hasOneJenisPengajuan?->jenis . ' (' . $item->hasOneTipePengajuan?->tipe . ')' ?? '';
                })
                ->addColumn('status', function ($item) {
                    if ($item->status == 'Tolak') {
                        $color = 'danger';
                    } else if ($item->status == 'Proses Verifikasi') {
                        $color = 'info';
                    } else if ($item->status == 'Selesai') {
                        $color = 'success';
                    } else if ($item->status == 'Input Data Pengajuan') {
                        $color = 'secondary';
                    } else {
                        $color = 'secondary';
                    }

                    return "<span class='badge badge-$color'>$item->status</span>";
                })
                ->addColumn('aksi', function ($item) {

                    if ($item->status != 'Input Data Pengajuan') {
                        $detailBtn = "<a href='/admin/permohonan/detail/$item->id' class='btn btn-primary btn-sm'>Detail</a>";
                        if ($item->status == 'Selesai') {
                            $verifikasiBtn = "<a href='/preview-surat-keputusan/{$item->id}' target='_blank' class='btn btn-info btn-sm'>Surat Keputusan</a>";
                        } else {
                            $verifikasiBtn = "<a href='/admin/permohonan/verifikasi-dokumen/$item->id' class='btn btn-warning btn-sm'>Aktivitas Permohonan</a>";
                        }
                        $latitude = $item->latitude;
                        $longitude = $item->longitude;

                        $btnLihatLokasi = "<a href='https://www.google.com/maps?q=$latitude,$longitude' target='_blank' class='btn btn-success btn-sm'>Lihat Lokasi</a>";
                    } else {
                        $detailBtn = "Harap Menunggu Pemohon Melakukan Input Data Pengajuan";
                        $verifikasiBtn = '';
                        $btnLihatLokasi  = '';
                    }

                    return "
                        <div class='btn-group' role='group'>
                            $detailBtn
                            $verifikasiBtn
                            $btnLihatLokasi
                        </div>
                    ";
                })
                ->rawColumns(['jenis', 'status', 'aksi'])
                ->make();
        }

        $userID = auth()->user()->id;

        $data = [
            'active' => 'pengajuan',
        ];

        return view('admin.pengajuan.index', $data);
    }

    public function redirect($pengajuanID)
    {
        $pengajuan = Pengajuan::with('hasOneRiwayatVerifikasi')->findOrFail($pengajuanID);


        if ($pengajuan->hasOneRiwayatVerifikasi->step == 'Verifikasi') {
            return redirect()->route('admin.verifikasi.dokumen', $pengajuanID);
        } else if ($pengajuan->hasOneRiwayatVerifikasi->step == 'Input Jadwal Tinjauan Lapangan') {
            return redirect()->route('admin.jadwal.tinjauan.lapangan', $pengajuanID);
        }
    }

    public function redirectAdmin($pengajuanID)
    {
        $pengajuan = Pengajuan::with('hasOneRiwayatVerifikasi')->findOrFail($pengajuanID);

        $riwayat = $pengajuan->hasOneRiwayatVerifikasi->step ?? '';

        if ($riwayat == 'Verifikasi') {
            $redirect = route('admin.verifikasi.dokumen', $pengajuanID);
            return $redirect;
        } else if ($riwayat == 'Input Jadwal Tinjauan Lapangan') {
            $redirect = route('admin.jadwal.tinjauan.lapangan', $pengajuanID);
            return $redirect;
        } else if ($riwayat == 'Tinjauan Lapangan') {
            $redirect = route('admin.tinjauan.lapangan', $pengajuanID);
            return $redirect;
        } else if ($riwayat == 'Menunggu Surat Kesanggupan') {
            $redirect = route('admin.menunggu.surat.kesanggupan', $pengajuanID);
            return $redirect;
        } else if ($riwayat == 'Verifikasi Surat Kesanggupan') {
            $redirect = route('admin.verifikasi.surat.kesanggupan', $pengajuanID);
            return $redirect;
        } else if ($riwayat == 'Membuat Surat Keputusan') {
            $redirect = route('admin.surat.keputusan', $pengajuanID);
            return $redirect;
        } else if ($riwayat == 'Menunggu Approve Surat Keputusan') {
            $redirect = route('admin.menunggu.approve.surat.keputusan', $pengajuanID);
            return $redirect;
        } else if ($riwayat == 'Selesai') {
            $redirect = route('admin.data.permohonan');
            return $redirect;
        }

        return null;
    }

    public function verifikasiDokumen($pengajuanID)
    {
        $redirect = $this->redirectAdmin($pengajuanID);

        if ($redirect && request()->fullUrl() != $redirect) {
            return redirect()->to($redirect);
        }

        $pengajuan = Pengajuan::with('hasOnePemohon', 'hasOneJenisPengajuan', 'hasOneTipePengajuan', 'hasManyDokumenPengajuan')->findorfail($pengajuanID);

        $status = $pengajuan->hasManyDokumenPengajuan->pluck('status')
            ->toArray();

        $filteredArray = array_filter($status, function ($value) {
            return !is_null($value);
        });

        if (empty($filteredArray)) {
            // semua nilai array null
            $rejectButton = '';
            $nextButton = 'hidden';
        } elseif (count($filteredArray) === count($status)) {
            // Semua nilai dalam array tidak null
            $nextButton = '';
            $rejectButton = 'hidden';
        } else {
            // Ada nilai yang tidak null dalam array
            $rejectButton = 'hidden';
            $nextButton = 'hidden';
        }

        $data = [
            'pengajuan' => $pengajuan,
            'rejectButton' => $rejectButton,
            'nextButton' => $nextButton
        ];

        return view('admin.pengajuan.verifikasi-dokumen', $data);
    }

    public function goToJadwalTinjauanLapangan($pengajuanID)
    {
        $pengajuan = Pengajuan::with('hasOnePemohon.hasOneProfile')->findOrFail($pengajuanID);

        $namaWebsite = env('APP_URL');

        $notification = new EmailNotificationController();
        $notification->sendEmail($pengajuan->user_id, "Admin telah menyetujui permohonan, dan telah membuat jadwal tinjauan lapangan.\nHarap melakukan pengecekan pada website $namaWebsite , untuk mengunduh jadwal tinjauan lapangan!");

        $pengajuan = Pengajuan::with('hasOneRiwayatPengajuan', 'hasOneRiwayatVerifikasi')->findorfail($pengajuanID);

        $pengajuan->status = 'Proses Permohonan';
        $pengajuan->save();

        $pengajuan->hasOneRiwayatVerifikasi()->create([
            'step' => 'Input Jadwal Tinjauan Lapangan'
        ]);

        return to_route('admin.jadwal.tinjauan.lapangan', $pengajuanID);
    }

    public function JadwalTinjauanLapangan($pengajuanID)
    {
        $redirect = $this->redirectAdmin($pengajuanID);

        if ($redirect && request()->fullUrl() != $redirect) {
            return redirect()->to($redirect);
        }

        $pengajuan = Pengajuan::findorfail($pengajuanID);

        $jadwals = JadwalTinjauanLapangan::with('belongsToPengajuan')->get();

        $arrJadwals = [];
        foreach ($jadwals as $jadwal) {
            $arrJadwals[] = [
                'title' => $jadwal->jam,
                'start' => $jadwal->getRawOriginal('tanggal'),
                'id' => $jadwal->id
            ];
        }

        $data = [
            'pengajuanID' => $pengajuanID,
            'pengajuan' => $pengajuan,
            'arrJadwals' => json_encode($arrJadwals), // Konversi array menjadi JSON
        ];

        return view('admin.pengajuan.buat-jadwal-tinjauan-lapangan', $data);
    }

    public function storeJadwalTinjauanLapangan(Request $request)
    {
        $jadwal = new JadwalTinjauanLapangan();
        $cekPengajuan = $jadwal->where('pengajuan_id', $request->pengajuan_id)->first();

        if ($cekPengajuan) {
            return redirect()->back()->with('failed', 'Anda telah menambahkan jadwal untuk pengajuan ini');
        }

        JadwalTinjauanLapangan::create([
            'pengajuan_id' => $request->pengajuan_id,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
            'deadline' => now()
        ]);

        RiwayatVerifikasi::where('pengajuan_id', $request->pengajuan_id)
            ->update([
                'step' => 'Tinjauan Lapangan'
            ]);

        RiwayatPengajuan::where('pengajuan_id', $request->pengajuan_id)
            ->update([
                'step' => 'Tinjauan Lapangan'
            ]);

        $pengajuan = Pengajuan::with('hasOnePemohon.hasOneProfile')->findOrFail($request->pengajuan_id);

        $namaWebsite = env('APP_URL');

        $notification = new EmailNotificationController();
        $notification->sendEmail($pengajuan->user_id, "Admin telah menyetujui permohonan, dan telah membuat jadwal tinjauan lapangan.\nHarap melakukan pengecekan pada website $namaWebsite , untuk mengunduh jadwal tinjauan lapangan!");

        // selanjutnya membuat stepper untuk tinjauan lapangan bersama
        // dd("halaman jadwal tinjauan lapangan bersama");
        return to_route('admin.tinjauan.lapangan', $request->pengajuan_id)->with('success', 'Berhasil Menambahkan Jadwal, Harap Lakukan Tinjauan Lapangan Sesuai Jadwal Yang Telah Dibuat');
    }

    public function tinjauanLapangan($pengajuanID)
    {
        $redirect = $this->redirectAdmin($pengajuanID);

        if ($redirect && request()->fullUrl() != $redirect) {
            return redirect()->to($redirect);
        }

        $pengajuan = Pengajuan::with('hasOnePemohon.hasOneProfile')->findorfail($pengajuanID);

        $data = [
            'pengajuan' => $pengajuan
        ];

        return view('admin.pengajuan.tinjauan-lapangan', $data);
    }

    public function ubahTinjauanLapangan(Request $request, $id)
    {
        // $jadwal = new JadwalTinjauanLapangan();

        JadwalTinjauanLapangan::where('id', $id)->update([
            'tanggal' => $request->tanggal,
            'jam' => $request->jam
        ]);

        // $data = $jadwal->with('belongsToPengajuan.belongsToUser.hasOneProfile', 'belongsToPengajuan.hasOneDataPemohon')->where('pengajuan_id', $request->pengajuan_id)->first();

        // $this->kirimNotifikasiJadwalDiubah($data);

        return redirect()->back()->with('success', 'Berhasil mengubah jadwal');
    }

    public function kirimNotifikasiJadwalDiubah($data)
    {
        // biarkan kosong next akan diubah jadi email
    }

    public function telahMelakukanTinjauan($jadwalID)
    {
        $jadwal = JadwalTinjauanLapangan::findorfail($jadwalID);

        JadwalTinjauanLapangan::where('id', $jadwalID)->update([
            'is_review' => true
        ]);

        RiwayatVerifikasi::updateorcreate([
            'pengajuan_id' => $jadwal->pengajuan_id,
        ], [
            'step' => 'Menunggu Surat Kesanggupan'
        ]);

        RiwayatPengajuan::updateorcreate([
            'pengajuan_id' => $jadwal->pengajuan_id,
        ], [
            'step' => 'Upload Surat Kesanggupan'
        ]);

        return to_route('admin.menunggu.surat.kesanggupan', $jadwal->pengajuan_id)->with('success', 'Terimakasih telah melakukan peninjauan lapangan');
    }

    public function menungguSuratKesanggupan($pengajuanID)
    {
        $redirect = $this->redirectAdmin($pengajuanID);

        if ($redirect && request()->fullUrl() != $redirect) {
            return redirect()->to($redirect);
        }

        return view('admin.pengajuan.menunggu-surat-kesanggupan');
    }

    public function verifikasiSuratKesanggupan($pengajuanID)
    {
        $redirect = $this->redirectAdmin($pengajuanID);

        if ($redirect && request()->fullUrl() != $redirect) {
            return redirect()->to($redirect);
        }

        $pengajuan = Pengajuan::with('hasOneSuratKesanggupan')->findorfail($pengajuanID);

        $data = [
            'pengajuan' => $pengajuan
        ];

        return view('admin.pengajuan.verifikasi-surat-kesanggupan', $data);
    }

    public function approveSuratKesanggupan(Request $request, $pengajuanID)
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
            'step' => 'Membuat Surat Keputusan'
        ]);

        RiwayatPengajuan::where('pengajuan_id', $pengajuanID)->update([
            'step' => 'Menunggu Surat Keputusan'
        ]);

        // $this->sendMessageToPemohon($pengajuanID);

        $pengajuan = Pengajuan::with('hasOnePemohon.hasOneProfile')->findOrFail($pengajuanID);

        $notification = new EmailNotificationController();
        $notification->sendEmail($pengajuan->user_id, 'Admin telah memverifikasi surat kesanggupan anda. Harap menunggu surat keputusan! Anda akan mendapat notifikasi jika surat keputusan telah dibuat.!');


        return to_route('admin.surat.keputusan', $pengajuanID)->with('success', 'Berhasil memverifikasi dan mengunggah');
    }

    public function sendMessageToPemohon($pengajuanID)
    {
        $pengajuan = Pengajuan::with('hasOnePemohon.hasOneProfile')->findOrFail($pengajuanID);

        $nomorHpPemohon = $pengajuan->hasOnePemohon->hasOneProfile->no_telepon;

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
                'target' => "$nomorHpPemohon", // nomer hp admin
                'message' => "Admin telah memverifikasi surat kesanggupan anda. Harap menunggu surat keputusan!\nAnda akan mendapat notifikasi jika surat keputusan telah dibuat.",
                'countryCode' => '62', //optional
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . config('fonnte.fonnte_token') . '' //change TOKEN to your actual token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    }

    public function suratKeputusan($pengajuanID)
    {
        $redirect = $this->redirectAdmin($pengajuanID);

        if ($redirect && request()->fullUrl() != $redirect) {
            return redirect()->to($redirect);
        }

        $pengajuan = Pengajuan::findorfail($pengajuanID);

        $data = [
            'pengajuanID' => $pengajuanID,
            'pengajuan' => $pengajuan
        ];

        return view('admin.pengajuan.surat-keputusan', $data);
    }

    public function kirimSuratKeputusanKeKasi($pengajuanID)
    {
        SuratKeputusan::updateorcreate([
            'pengajuan_id' => $pengajuanID
        ], [
            'status' => 'Persetujuan Kasi'
        ]);

        RiwayatVerifikasi::where('pengajuan_id', $pengajuanID)->update([
            'step' => 'Menunggu Approve Surat Keputusan'
        ]);

        // $this->sendMessageToKasi();

        $notification = new EmailNotificationController();

        $users = User::where('role', 'kasi')->get();

        foreach ($users as $user) {
            $notification->sendEmail($user->id, 'Admin telah mengirimkan surat keputusan! Harap melakukan verifikasi pada surat keputusan tersebut.!');
        }

        $pengajuan = Pengajuan::with('hasOnePemohon.hasOneProfile')->findOrFail($pengajuanID);

        $notification = new EmailNotificationController();
        $notification->sendEmail($pengajuan->user_id, 'Admin telah memverifikasi surat kesanggupan anda. Harap menunggu surat keputusan! Anda akan mendapat notifikasi jika surat keputusan telah dibuat.!');

        return to_route('admin.menunggu.approve.surat.keputusan', $pengajuanID)->with('success', 'Berhasil mengirim surat keputusan ke KASI');
    }

    public function kirimSuratKeputusanKeBantara($pengajuanId)
    {
        // dd(auth()->user()->hasOneProfile->no_ktp);

        $logoPath = public_path('img/kab-bantul.png');
        $encodeLogo = base64_encode(file_get_contents($logoPath));

        $aksaraPath = public_path('img/aksara-dishub.png');
        $encodeAksara = base64_encode(file_get_contents($aksaraPath));

        $pengajuan = Pengajuan::with('hasOneJadwalTinjauan', 'hasOnePemohon.hasOneProfile')->findOrFail($pengajuanId);

        \Carbon\Carbon::setLocale('id');
        $tanggal = \Carbon\Carbon::parse($pengajuan->hasOneJadwalTinjauan->tanggal)->translatedFormat('L F Y');
        $hari = \Carbon\Carbon::parse($pengajuan->hasOneJadwalTinjauan->tanggal)->translatedFormat('l');
        $tanggalSurat = \Carbon\Carbon::parse($pengajuan->hasOneJadwalTinjauan->created_at)->translatedFormat('L F Y');

        $kepada = [];
        $kepada['pemohon'] = $pengajuan->hasOnePemohon->hasOneProfile->nama;

        $kadis = User::with('hasOneProfile')->where('role', 'kadis')->first();

        $data = [
            'aksara' => $encodeAksara,
            'logo' => $encodeLogo,
            'pengajuan' => $pengajuan,
            'tanggal' => $tanggal,
            'hari' => $hari,
            'tanggalSurat' => $tanggalSurat,
            'kepada' => $kepada,
            'kadis' => $kadis
        ];

        $pdf = PDF::loadView('template.surat-keputusan', $data);

        $baseUrl = env('BANTARA_BASE_URL');
        $secretKey = env('BANTARA_SECRET_KEY');

        // Generate callback URL
        $callbackUrl = route('callback.bantara');

        $response = Http::withToken($secretKey)
            ->attach(
                'file',
                $pdf->output(),
                'document.pdf'
            )
            ->post($baseUrl . '/tte/documents', [
                'title'         => 'Surat Keputusan',
                'description'   => 'Surat Keputusan',
                'signer_nik'    => env('BANTARA_NIK'),
                'callback_url'  => $callbackUrl,
                'callback_key'  => env('BANTARA_CALLBACK_KEY'),
                'with_footer'   => false,
            ]);

        // Cek response
        if ($response->successful()) {
            $responseData = $response->json();

            // Simpan bantara_document_id dari response
            if (isset($responseData['id'])) {
                $pengajuan->update([
                    'bantara_document_id' => $responseData['id'],
                    'status_surat_keputusan' => 'uploaded'
                ]);

                return redirect()->back()->with('success', 'Surat keputusan berhasil dikirim ke BANTARA untuk ditandatangani secara elektronik');
            }
        }

        // Jika gagal
        return redirect()->back()->with('failed', 'Gagal mengirim surat keputusan ke BANTARA: ' . $response->body());
    }

    public function callbackBantara(Request $request)
    {
        // Validate callback key jika ada
        $callbackKey = env('BANTARA_CALLBACK_KEY');
        if ($callbackKey) {
            $receivedKey = $request->header('X-Callback-Key');
            if ($receivedKey !== $callbackKey) {
                \Log::warning('Invalid BANTARA callback key');
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }

        // Ambil document ID dari request
        $documentId = $request->input('id');

        if (!$documentId) {
            \Log::warning('BANTARA callback missing document ID');
            return response()->json(['error' => 'Missing document ID'], 400);
        }

        // Cari pengajuan berdasarkan bantara_document_id
        $pengajuan = Pengajuan::where('bantara_document_id', $documentId)->first();

        if (!$pengajuan) {
            \Log::warning("Pengajuan with bantara_document_id $documentId not found");
            return response()->json(['error' => 'Document not found'], 404);
        }

        // Cek apakah ada file yang dikirim (signed document)
        if ($request->hasFile('file')) {
            // Simpan file surat keputusan yang sudah ditandatangani
            $file = $request->file('file');
            $filename = time() . "-Surat-Keputusan-Signed." . $file->getClientOriginalExtension();
            $location = 'file-uploads/Surat Keputusan/' . $pengajuan->id . '/';
            $filepath = $location . $filename;
            $file->storeAs('public/' . $location, $filename, 'public');

            // Update pengajuan
            $pengajuan->update([
                'surat_keputusan' => $filepath,
                'status_surat_keputusan' => 'signed'
            ]);

            \Log::info("BANTARA: Document $documentId successfully signed for pengajuan {$pengajuan->id}");

            return response()->json(['success' => true]);
        } else {
            // Jika tidak ada file, berarti proses TTE gagal
            $pengajuan->update([
                'status_surat_keputusan' => 'failed'
            ]);

            \Log::error("BANTARA: Document $documentId signing failed for pengajuan {$pengajuan->id}");

            return response()->json(['error' => 'Signing failed'], 422);
        }
    }

    public function getSignedDocumentFromBantara($pengajuanId)
    {
        $pengajuan = Pengajuan::findOrFail($pengajuanId);

        // Cek apakah ada bantara_document_id
        if (!$pengajuan->bantara_document_id) {
            return redirect()->back()->with('failed', 'Document ID BANTARA tidak ditemukan. Silakan kirim surat keputusan ke BANTARA terlebih dahulu.');
        }

        $baseUrl = env('BANTARA_BASE_URL');
        $secretKey = env('BANTARA_SECRET_KEY');

        // Request ke BANTARA untuk mendapatkan signed document
        $response = Http::withToken($secretKey)
            ->get($baseUrl . '/tte/signeddocument/' . $pengajuan->bantara_document_id);

        // Handle response berdasarkan status code
        if ($response->status() === 200) {
            // Success - simpan file PDF
            $pdfContent = $response->body();
            $filename = time() . "-Surat-Keputusan-Signed.pdf";
            $location = 'file-uploads/Surat Keputusan/' . $pengajuan->id . '/';
            $filepath = $location . $filename;

            // Simpan file
            \Storage::disk('public')->put($location . $filename, $pdfContent);

            // Update pengajuan
            $pengajuan->update([
                'surat_keputusan' => $filepath,
                'status_surat_keputusan' => 'signed'
            ]);

            \Log::info("BANTARA: Successfully retrieved signed document for pengajuan {$pengajuan->id}");

            return redirect()->back()->with('success', 'Surat keputusan berhasil diambil dari BANTARA');
        } elseif ($response->status() === 404) {
            \Log::warning("BANTARA: Document not found for pengajuan {$pengajuan->id}");
            return redirect()->back()->with('failed', 'Dokumen tidak ditemukan di BANTARA');
        } elseif ($response->status() === 422) {
            \Log::info("BANTARA: Document not yet signed for pengajuan {$pengajuan->id}");
            return redirect()->back()->with('failed', 'Dokumen belum ditandatangani. Silakan tunggu proses TTE selesai.');
        } elseif ($response->status() === 403) {
            \Log::error("BANTARA: Access denied for pengajuan {$pengajuan->id}");
            return redirect()->back()->with('failed', 'Akses ditolak. Periksa kredensial BANTARA.');
        } else {
            \Log::error("BANTARA: Unknown error for pengajuan {$pengajuan->id} - Status: " . $response->status());
            return redirect()->back()->with('failed', 'Terjadi kesalahan: ' . $response->body());
        }
    }

    public function sendMessageToKasi()
    {
        $kasi = User::with('hasOneProfile')->where('role', 'kasi')->first();

        $noHpKasi = $kasi->hasOneProfile->no_telepon;

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
                'target' => "$noHpKasi", // nomer hp admin
                'message' => "Admin telah mengirimkan surat keputusan!\nHarap melakukan verifikasi pada surat keputusan tersebut.",
                'countryCode' => '62', //optional
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . config('fonnte.fonnte_token') . '' //change TOKEN to your actual token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    }

    public function menungguApproveSuratKeputusan($pengajuanID)
    {
        $redirect = $this->redirectAdmin($pengajuanID);

        if ($redirect && request()->fullUrl() != $redirect) {
            return redirect()->to($redirect);
        }

        $pengajuan = Pengajuan::findorfail($pengajuanID);

        $data = [
            'pengajuanID' => $pengajuanID,
            'pengajuan' => $pengajuan
        ];

        return view('admin.pengajuan.menunggu-approve-surat-keputusan', $data);
    }
}
