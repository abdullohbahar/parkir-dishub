<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id',
        'user_id',
        'nama',
        'no_ktp',
        'alamat',
        'no_telepon',
        'agama',
        'pendidikan_terakhir',
        'tempat_lahir',
        'tanggal_lahir'
    ];
}
