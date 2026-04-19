<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jadwal extends Model
{
    protected $fillable = [
        'kode','aset_id','kantor_id','jenis','teknisi',
        'tanggal','waktu','catatan','status',
    ];

    protected $casts = ['tanggal' => 'date'];

    public function aset(): BelongsTo   { return $this->belongsTo(Aset::class); }
    public function kantor(): BelongsTo { return $this->belongsTo(Kantor::class); }

    public function getTanggalFormattedAttribute(): string
    {
        return $this->tanggal?->translatedFormat('d M Y') ?? '-';
    }
}