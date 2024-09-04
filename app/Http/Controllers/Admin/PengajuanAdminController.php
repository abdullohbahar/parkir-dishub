<?php

namespace App\Http\Controllers\Admin;

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
use App\Models\JadwalTinjauanLapangan;
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
                            $verifikasiBtn = "<a href='/surat-keputusan/$item->id' target='_blank' class='btn btn-success btn-sm'>Surat Keputusan</a>";
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
        $sendRevisiMessageToPemohon = new SendRevisiMessageToPemohon();
        $sendRevisiMessageToPemohon->__invoke($pengajuanID);

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

        $data = $jadwal->with('belongsToPengajuan.hasOnePemohon.hasOneProfile')->where('pengajuan_id', $request->pengajuan_id)->first();

        $sendApprovedToPemohon = new SendApprovedToPemohon();
        $sendApprovedToPemohon->__invoke($data);

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

        $this->sendMessageToPemohon($pengajuanID);

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

        $this->sendMessageToKasi();

        return to_route('admin.menunggu.approve.surat.keputusan', $pengajuanID)->with('success', 'Berhasil mengirim surat keputusan ke KASI');
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
