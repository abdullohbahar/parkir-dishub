<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;

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

        if ($pengajuan->status == 'Proses Verifikasi Admin') {
            return to_route('admin.verifikasi.dokumen', $pengajuanID);
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
}
