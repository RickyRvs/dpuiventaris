<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aset extends Model
{
    protected $fillable = [
        'kode', 'nama', 'kategori', 'kantor_id', 'ruangan',
        'kondisi', 'nilai', 'tanggal_pengadaan', 'serial_number',
        'penanggung_jawab', 'merek', 'model',
        'garansi_bulan', 'garansi_habis', 'catatan',
    ];

    protected $casts = [
        'tanggal_pengadaan' => 'date',
        'garansi_habis'     => 'date',
        'nilai'             => 'decimal:2',
    ];

    public function kantor(): BelongsTo
    {
        return $this->belongsTo(Kantor::class);
    }

    public function mutasis(): HasMany
    {
        return $this->hasMany(Mutasi::class);
    }

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }

    /** Helper: nilai formatted Rupiah */
    public function getNilaiFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->nilai, 0, ',', '.');
    }

    /** Helper: kondisi badge class */
    public function getBadgeClassAttribute(): string
    {
        return match($this->kondisi) {
            'Baik'           => 'badge-baik',
            'Rusak'          => 'badge-rusak',
            default          => 'badge-perbaikan',
        };
    }
}