<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKesanggupan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'pengajuan_id',
        'file',
        'deadline'
    ];
}
