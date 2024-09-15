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
        Schema::create('riwayat_verifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('pengajuan_id')->nullable()->references('id')->on('pengajuans')->nullOnDelete();
            $table->enum('step', ['Verifikasi', 'Input Jadwal Tinjauan Lapangan', 'Tinjauan Lapangan', 'Menunggu Surat Kesanggupan', 'Verifikasi Surat Kesanggupan', 'Membuat Surat Keputusan', 'Menunggu Approve Surat Keputusan', 'Selesai']);
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
        Schema::dropIfExists('riwayat_verifikasis');
    }
};
