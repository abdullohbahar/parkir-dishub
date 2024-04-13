<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeJalan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'jenis_jalan_id',
        'tipe'
    ];
}
