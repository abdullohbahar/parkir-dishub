<?php

namespace App\Http\Controllers\Pemohon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardPemohonController extends Controller
{
    public function index()
    {
        return view('pemohon.dashboard.index');
    }
}
