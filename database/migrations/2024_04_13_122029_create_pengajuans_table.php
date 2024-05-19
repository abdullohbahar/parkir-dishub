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
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignUuid('jenis_pengajuan_id')->references('id')->on('jenis_pengajuans')->onDelete('cascade');
            $table->foreignUuid('tipe_pengajuan_id')->references('id')->on('tipe_pengajuans')->onDelete('cascade');
            $table->text('lokasi_pengelolaan_parkir')->nullable();
            $table->text('alamat_lokasi_parkir')->nullable();
            $table->string('panjang')->nullable();
            $table->string('luas')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->enum('status', ['Input Data Pengajuan', 'Proses Verifikasi Admin', 'Proses Permohonan', 'Tolak', 'Selesai']);
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
        Schema::dropIfExists('pengajuans');
    }
};
