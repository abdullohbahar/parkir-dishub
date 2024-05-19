<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalTinjauanLapangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'pengajuan_id',
        'tanggal',
        'jam',
        'is_review',
        'deadline'
    ];

    public function belongsToPengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id', 'id');
    }
}
