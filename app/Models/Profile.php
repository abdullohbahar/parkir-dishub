<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'tanggal_lahir',
        'nip',
        'foto_profile'
    ];

    public function getTtlAttribute()
    {
        return $this->attributes['tempat_lahir'] . ', ' . Carbon::parse($this->attributes['tanggal_lahir'])->format('d-m-Y');
    }

    public function getTranslatedTtlAttribute()
    {
        Carbon::setLocale('id');
        return $this->attributes['tempat_lahir'] . ', ' . Carbon::parse($this->attributes['tanggal_lahir'])->translatedFormat('d F Y');
    }

    protected function fotoProfile(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? asset('storage/file-uploads/foto-profile/' . $value) : asset('img/default.jpg')
        );
    }
}
