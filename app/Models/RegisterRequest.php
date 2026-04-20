<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegisterRequest extends Model
{
    protected $table = 'register_requests';

    protected $fillable = [
        'nama', 'email', 'password', 'peran',
        'kantor_id',  // legacy, nullable
        'kantor_ids', // ✅ array kantor multi-pilih
        'alasan', 'status', 'initials', 'color1', 'color2',
        'catatan_admin', 'approved_at', 'approved_by',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'approved_at' => 'datetime',
        'kantor_ids'  => 'array', // ✅ auto JSON encode/decode
    ];

    // ── Relations ──────────────────────────────────────────────

    public function kantor(): BelongsTo
    {
        return $this->belongsTo(Kantor::class, 'kantor_id');
    }

    /** Ambil koleksi Kantor dari kantor_ids (atau fallback ke kantor_id lama). */
    public function getKantorListAttribute()
    {
        if (!empty($this->kantor_ids)) {
            return Kantor::whereIn('id', $this->kantor_ids)->get();
        }
        if ($this->kantor_id) {
            return Kantor::where('id', $this->kantor_id)->get();
        }
        return collect();
    }

    /** Nama kantor dipisah koma. Contoh: "Pekanbaru, Tebet Jakarta" */
    public function getKantorNamaListAttribute(): string
    {
        return $this->kantor_list->pluck('short_name')->join(', ') ?: '-';
    }

    // ── Scopes ─────────────────────────────────────────────────

    public function scopeMenunggu($query)   { return $query->where('status', 'Menunggu'); }
    public function scopeDisetujui($query)  { return $query->where('status', 'Disetujui'); }
    public function scopeDitolak($query)    { return $query->where('status', 'Ditolak'); }

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