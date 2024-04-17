<?php

namespace App\Http\Controllers\Pemohon;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;

class PengajuanPermohonanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $userID = auth()->user()->id;

            $query = Pengajuan::with('hasOnePemohon', 'hasOneJenisPengajuan', 'hasOneTipePengajuan')
                ->orderBy('updated_at', 'desc')
                ->where('user_id', $userID)->get();

            // return $query;
            return Datatables::of($query)
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

                    if ($item->status != 'input data belum selesai') {
                        $detailBtn = "<a href='/pengajuan/andalalin/detail/$item->id' class='btn btn-primary btn-sm'>Detail</a>";
                        // if ($item->hasOneRiwayatInputData->step == 'Selesai') {
                        //     $verifikasiBtn = '';
                        // } else {
                        $verifikasiBtn = "<a href='/pengajuan/andalalin/riwayat-input-data/$item->id' class='btn btn-warning btn-sm'>Aktivitas Permohonan</a>";
                        // }
                    } else {
                        $detailBtn = "<a href='/pengajuan/andalalin/riwayat-input-data/$item->id' class='btn btn-info btn-sm'>Lanjutkan Mengisi Data</a>";
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

        return view('pemohon.pengajuan.index', $data);
    }
}
