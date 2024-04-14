<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipePengajuan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id',
        'jenis_pengajuan_id',
        'tipe'
    ];

    public function belongsToJenisPengajaun(): BelongsTo
    {
        return $this->belongsTo(JenisPengajuan::class);
    }
}
