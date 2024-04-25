<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Message\SendApprovedToPemohon;
use DataTables;
use App\Models\Pengajuan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RiwayatVerifikasi;
use App\Http\Controllers\Controller;
use App\Models\JadwalTinjauanLapangan;
use App\Http\Controllers\Admin\Message\SendRevisiMessageToPemohon;
use App\Models\RiwayatPengajuan;

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
                        $detailBtn = "<a href='/admin/permohonan/verifikasi-dokumen/$item->id' class='btn btn-primary btn-sm'>Detail</a>";
                        // if ($item->hasOneRiwayatInputData->step == 'Selesai') {
                        //     $verifikasiBtn = '';
                        // } else {
                        $verifikasiBtn = "<a href='/admin/permohonan/verifikasi-dokumen/$item->id' class='btn btn-warning btn-sm'>Aktivitas Permohonan</a>";
                        // }
                    } else {
                        $detailBtn = "Harap Menunggu Pemohon Melakukan Input Data Pengajuan";
                        $verifikasiBtn = '';
                    }

                    return "
                        <div class='btn-group' role='group'>
                            $detailBtn
                            $verifikasiBtn
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

    public function verifikasiDokumen($pengajuanID)
    {
        $this->redirect($pengajuanID);

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
        $pengajuan = Pengajuan::with('hasOnePemohon.hasOneProfile')->findorfail($pengajuanID);

        $data = [
            'pengajuan' => $pengajuan
        ];

        return view('admin.pengajuan.tinjauan-lapangan', $data);
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
        dd('menunggu surat kesanggupan');
    }
}
