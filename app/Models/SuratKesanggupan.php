<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuratKesanggupan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'pengajuan_id',
        'file',
        'deadline'
    ];

    protected function file(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => asset('/storage/' . $value)
        );
    }
}
