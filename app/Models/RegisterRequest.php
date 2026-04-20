<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegisterRequest extends Model
{
    protected $table = 'register_requests';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'peran',
        'kantor_id',
        'alasan',
        'status',       // Menunggu | Disetujui | Ditolak
        'initials',
        'color1',
        'color2',
        'catatan_admin',
        'approved_at',
        'approved_by',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    // ── Relations ──────────────────────────────────────────────

    public function kantor(): BelongsTo
    {
        return $this->belongsTo(Kantor::class, 'kantor_id');
    }

    // ── Scopes ─────────────────────────────────────────────────

    public function scopeMenunggu($query)
    {
        return $query->where('status', 'Menunggu');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'Disetujui');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'Ditolak');
    }

    // ── Accessors ──────────────────────────────────────────────

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'Menunggu'  => '#f97316',
            'Disetujui' => '#16a34a',
            'Ditolak'   => '#ef4444',
            default     => '#94a3b8',
        };
    }

    public function getStatusBgAttribute(): string
    {
        return match ($this->status) {
            'Menunggu'  => '#fff7ed',
            'Disetujui' => '#f0fdf4',
            'Ditolak'   => '#fef2f2',
            default     => '#f8fafc',
        };
    }
}