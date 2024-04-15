<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function hasOnePemohon(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function hasOneJenisPengajuan(): HasOne
    {
        return $this->hasOne(JenisPengajuan::class);
    }

    public function hasOneTipePengajuan(): HasOne
    {
        return $this->hasOne(TipePengajuan::class);
    }
}
