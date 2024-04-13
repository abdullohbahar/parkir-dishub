<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayat_pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('pengajuan_id')->references('id')->on('pengajuans')->onDelete('cascade');
            $table->enum('step', ['Memilih Pengajuan', 'Input Data Pengajuan', 'Upload Dokumen Pengajuan', 'Menunggu Verifikasi Admin', 'Tinjauan Lapangan', 'Upload Surat Kesanggupan', 'Menunggu Verifikasi Surat Kesanggupan', 'Selesai']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('riwayat_pengajuans');
    }
};
