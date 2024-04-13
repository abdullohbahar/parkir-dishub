<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisJalan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'jenis'
    ];
}
