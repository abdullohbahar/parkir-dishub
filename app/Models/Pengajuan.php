<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pengajuan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id',
        'user_id',
        'jenis_pengajuan_id',
        'tipe_pengajuan_id',
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
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function hasOneJenisPengajuan(): HasOne
    {
        return $this->hasOne(JenisPengajuan::class, 'id', 'jenis_pengajuan_id');
    }

    public function hasOneTipePengajuan(): HasOne
    {
        return $this->hasOne(TipePengajuan::class, 'id', 'tipe_pengajuan_id');
    }

    public function hasOneRiwayatPengajuan(): HasOne
    {
        return $this->hasOne(RiwayatPengajuan::class);
    }

    public function hasOneRiwayatVerifikasi(): HasOne
    {
        return $this->hasOne(RiwayatVerifikasi::class);
    }

    public function hasManyDokumenPengajuan(): HasMany
    {
        return $this->hasMany(DokumenPengajuan::class);
    }

    public function hasOneDokumenPengajuan(): HasOne
    {
        return $this->hasOne(DokumenPengajuan::class);
    }
}
