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
        Schema::table('dokumen_pengajuans', function (Blueprint $table) {
            $table->enum('status', ['Ditolak', 'Disetujui', 'Revisi'])->after('alasan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dokumen_pengajuans', function (Blueprint $table) {
            //
        });
    }
};
