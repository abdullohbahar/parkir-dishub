<?php

namespace App\Http\Controllers\Pemohon\Pengajuan;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TinjauanLapanganController extends Controller
{
    public function index($pengajuanID)
    {
        $inputDataPermohonanController = new InputDataPermohonanController();

        $redirect = $inputDataPermohonanController->redirectPemohon($pengajuanID);

        if ($redirect && request()->fullUrl() != $redirect) {
            return redirect()->to($redirect);
        }

        $pengajuan = Pengajuan::with('hasOnePemohon.hasOneProfile')->findorfail($pengajuanID);

        $data = [
            'pengajuan' => $pengajuan
        ];

        return view('pemohon.pengajuan.tinjauan-lapangan', $data);
    }
}
