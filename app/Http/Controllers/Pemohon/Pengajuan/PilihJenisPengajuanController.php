<?php

namespace App\Http\Controllers\Pemohon\Pengajuan;

use App\Http\Controllers\Controller;
use App\Models\JenisPengajuan;
use App\Models\Pengajuan;
use App\Models\TipePengajuan;
use Illuminate\Http\Request;

class PilihJenisPengajuanController extends Controller
{
    public function index()
    {
        $jenisPengajuans = JenisPengajuan::get();

        $data = [
            'jenisPengajuans' => $jenisPengajuans
        ];

        return view('pemohon.pengajuan.pilih-jenis-pengajuan', $data);
    }

    public function store(Request $request)
    {
        $userID = auth()->user()->id;

        $pengajuan = Pengajuan::create([
            'user_id' => $userID,
            'jenis_pengajuan_id' => $request->jenis_pengajuan_id,
            'tipe_pengajuan_id' => $request->tipe_pengajuan_id,
            'status' => 'Input Data Pengajuan'
        ]);

        $pengajuan->hasOneRiwayatPengajuan()->create([
            'step' => 'Input Data Pengajuan'
        ]);

        return to_route('pemohon.input.data.permohonan', $pengajuan->id)->with('success', 'Berhasil');
    }

    public function getTipePengajuan($jenisPengajuanID)
    {
        $tipePengajuan = TipePengajuan::where('jenis_pengajuan_id', $jenisPengajuanID)
            ->orderBy('tipe', 'asc')
            ->get();

        if ($tipePengajuan) {
            return response()->json([
                'data' => $tipePengajuan,
            ], 200);
        } else {
            return response()->json([], 404);
        }
    }
}
