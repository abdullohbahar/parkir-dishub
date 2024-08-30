<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPengajuan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id',
        'pengajuan_id',
        'nama_dokumen',
        'file'
    ];

    protected function file(): Attribute
    {
        return Attribute::make(
            get: fn($value) => asset('/storage/public/' . $value)
        );
    }
}
