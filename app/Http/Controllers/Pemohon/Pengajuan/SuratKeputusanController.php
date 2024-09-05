<?php

namespace App\Http\Controllers\Pemohon\Pengajuan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuratKeputusanController extends Controller
{
    public function menungguVerifikasi($pengajuanID)
    {
        return view('pemohon.pengajuan.menunggu-verifikasi-surat-keputusan');
    }
}
