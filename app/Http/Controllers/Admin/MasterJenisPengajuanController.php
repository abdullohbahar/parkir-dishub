<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisPengajuan;
use Illuminate\Http\Request;

class MasterJenisPengajuanController extends Controller
{
    public function index()
    {
        $jenisPengajuan = JenisPengajuan::with('hasManyTipePengajuan')->get();

        $data = [
            'jenisPengajuan' => $jenisPengajuan
        ];

        return view('admin.master-jenis-pengajuan.index', $data);
    }
}
