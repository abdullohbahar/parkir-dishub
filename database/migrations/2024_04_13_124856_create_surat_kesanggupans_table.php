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
        Schema::create('surat_kesanggupans', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('pengajuan_id')->references('id')->on('pengajuans')->onDelete('cascade');
            $table->text('file');
            $table->date('deadline');
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
        Schema::dropIfExists('surat_kesanggupans');
    }
};
