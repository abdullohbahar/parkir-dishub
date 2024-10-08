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
        Schema::create('jadwal_tinjauan_lapangans', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('pengajuan_id')->nullable()->references('id')->on('pengajuans')->nullOnDelete();
            $table->date('tanggal');
            $table->boolean('is_review')->default(0);
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
        Schema::dropIfExists('jadwal_tinjauan_lapangans');
    }
};
