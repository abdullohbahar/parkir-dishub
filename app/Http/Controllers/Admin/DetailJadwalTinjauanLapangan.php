<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalTinjauanLapangan;
use Illuminate\Http\Request;

class DetailJadwalTinjauanLapangan extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($jadwalID)
    {
        $jadwal = JadwalTinjauanLapangan::with('belongsToPengajuan.hasOnePemohon.hasOneProfile')->where('id', $jadwalID)->first();

        $data = [
            'alamat' => $jadwal->belongsToPengajuan->alamat_lokasi_parkir,
            'tanggal_tinjauan' => $jadwal->tanggal,
            'jam_tinjauan' => $jadwal->jam
        ];

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }
}
