<?php

namespace App\Http\Controllers\Pemohon;

use DataTables;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Models\DokumenPengajuan;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RandomString;
use Illuminate\Support\Facades\File;

class PengajuanPermohonanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $userID = auth()->user()->id;

            $query = Pengajuan::with([
                'hasOnePemohon',
                'hasOneJenisPengajuan',
                'hasOneTipePengajuan',
                'hasOneDokumenPengajuan' => function ($query) {
                    return $query->where('status', 'Revisi');
                }
            ])
                ->orderBy('updated_at', 'desc')
                ->where('user_id', $userID)->get();

            // return $query;
            return Datatables::of($query)
                ->addColumn('jenis', function ($item) {
                    return $item->hasOneJenisPengajuan?->jenis . ' (' . $item->hasOneTipePengajuan?->tipe . ')' ?? '';
                })
                ->addColumn('status', function ($item) {
                    if ($item->hasOneDokumenPengajuan) {
                        $statusRevisi = "<span class='badge badge-warning text-dark mt-2 ml-2'>Perlu Revisi Dokumen Permohonan</span>";
                    } else {
                        $statusRevisi = '';
                    }

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

                    return "<span class='badge badge-$color mt-2 ml-2'>$item->status</span><br>$statusRevisi";
                })
                ->addColumn('aksi', function ($item) {
                    if ($item->hasOneDokumenPengajuan) {
                        $btnRevisi = "<a href='/pemohon/permohonan/revisi/$item->id' class='btn btn-info btn-sm'>Revisi</a>";
                    } else {
                        $btnRevisi = '';
                    }

                    if ($item->status != 'input data belum selesai') {
                        $detailBtn = "<a href='/pemohon/permohonan/detail/$item->id' class='btn btn-primary btn-sm'>Detail</a>";
                        if ($item->status == 'Selesai') {
                            $verifikasiBtn = "<a href='/surat-keputusan/$item->id' target='_blank' class='btn btn-success btn-sm'>Surat Keputusan</a>";
                        } else {
                            $verifikasiBtn = "<a href='/pemohon/permohonan/input-data-permohonan/$item->id' class='btn btn-warning btn-sm text-dark'>Aktivitas Permohonan</a>";
                        }

                        $latitude = $item->latitude;
                        $longitude = $item->longitude;

                        $btnLihatLokasi = "<a href='https://www.google.com/maps?q=$latitude,$longitude' target='_blank' class='btn btn-success btn-sm'>Lihat Lokasi</a>";
                    } else {
                        $detailBtn = "<a href='/pengajuan/andalalin/riwayat-input-data/$item->id' class='btn btn-info btn-sm'>Lanjutkan Mengisi Data</a>";
                        $verifikasiBtn = '';
                        $btnLihatLokasi = '';
                    }

                    return "
                        <div class='btn-group' role='group'>
                            $detailBtn
                            $btnRevisi
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

        return view('pemohon.pengajuan.index', $data);
    }

    public function detail($pengajuanID)
    {
        $pengajuan = Pengajuan::with('hasOnePemohon.hasOneProfile', 'hasOneJenisPengajuan')->findorfail($pengajuanID);

        $data = [
            'pengajuan' => $pengajuan
        ];

        return view('pemohon.pengajuan.detail-data-permohonan', $data);
    }

    public function revisiPage($pengajuanID)
    {
        $pengajuan = Pengajuan::with('hasManyDokumenPengajuan')->findOrFail($pengajuanID);

        $data = [
            'pengajuan' => $pengajuan
        ];

        return view('pemohon.pengajuan.revisi-dokumen-permohonan', $data);
    }

    public function revisiAction(Request $request)
    {
        $userID = auth()->user()->id;

        // get random string
        $randomString = new RandomString();

        $file = $request->file('file');
        $filename = $randomString->__invoke() . time() . " - $request->nama_dokumen ." . $file->getClientOriginalExtension();
        $location = 'file-uploads/Dokumen Pengajuan/'  . $userID .  '/';
        $filepath = $location . $filename;

        $dokumen = DokumenPengajuan::findorfail($request->dokumenID);

        if (file_exists($dokumen->file)) {
            File::delete($dokumen->file);
        }

        $file->storeAs('public/' . $location, $filename, 'public');

        DokumenPengajuan::where(
            'id',
            $request->dokumenID
        )->update([
            'file' => $filepath,
            'alasan' => null,
            'status' => null
        ]);

        return redirect()->back()->with('success', 'Berhasil mengunggah');
    }
}
