<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipePengajuan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id',
        'jenis_jalan_id',
        'tipe'
    ];
}
