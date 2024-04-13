<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id',
        'user_id',
        'jenis_jalan_id',
        'lokasi_pengelolaan_parkir',
        'alamat_lokasi_parkir',
        'panjang',
        'luas',
        'longitude',
        'latitude',
        'status'
    ];
}
