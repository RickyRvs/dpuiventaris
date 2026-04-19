<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ✅ FIX BUG 6: Model AuditLog sebelumnya kosong — tidak ada $fillable
 * Blade audit-log pakai $log['user'], $log['aksi'], $log['waktu'] via accessor
 */
class AuditLog extends Model
{
    protected $fillable = [
        'user_name',
        'aksi',
        'modul',
        'icon',
        'bg',
        'ic',
    ];

    /**
     * Blade pakai $log['waktu'] — tapi karena Eloquent,
     * pakai $log->waktu via accessor ini
     */
    public function getWaktuAttribute(): string
    {
        return $this->created_at?->translatedFormat('d M Y, H:i') ?? '-';
    }

    /**
     * Blade pakai $log['user'] — accessor untuk kompatibilitas
     */
    public function getUserAttribute(): string
    {
        return $this->user_name ?? 'System';
    }
}