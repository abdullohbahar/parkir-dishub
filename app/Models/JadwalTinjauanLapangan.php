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
        'tempat',
        'is_review',
        'deadline'
    ];
}
