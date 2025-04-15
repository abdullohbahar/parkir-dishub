<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratKeputusan;

class PreviewSuratKeputusanController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($pengajuanID)
    {
        // Cari data surat keputusan berdasarkan pengajuanID
        $suratKeputusan = SuratKeputusan::where('pengajuan_id', $pengajuanID)->first();

        return response()->file(public_path($suratKeputusan->file));
        // Cek apakah data ditemukan dan file path ada
        if ($suratKeputusan && file_exists(public_path($suratKeputusan->file))) {
            // Menampilkan file langsung di browser
        } else {
            // Jika file tidak ditemukan atau data tidak ada, kembalikan pesan error
            return response()->json(['error' => 'File not found or record does not exist.'], 404);
        }
    }
}
