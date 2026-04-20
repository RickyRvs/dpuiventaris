<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'peran',
        'kantor_id',   // legacy single kantor (nullable)
        'initials',
        'color1',
        'color2',
        'last_login_at',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    // ── Relations ──────────────────────────────────────────────

    /**
     * Legacy: single kantor (untuk backward compat).
     */
    public function kantor(): BelongsTo
    {
        return $this->belongsTo(Kantor::class, 'kantor_id');
    }

    /**
     * ✅ Multi kantor via pivot table user_kantors.
     */
    public function kantors(): BelongsToMany
    {
        return $this->belongsToMany(Kantor::class, 'user_kantors', 'user_id', 'kantor_id')
                    ->withTimestamps();
    }

    // ── Helpers ────────────────────────────────────────────────

    /**
     * Ambil semua kantor user (pivot dulu, fallback ke legacy kantor_id).
     */
    public function getAllKantorsAttribute()
    {
        $pivotKantors = $this->kantors;
        if ($pivotKantors->isNotEmpty()) {
            return $pivotKantors;
        }
        if ($this->kantor_id) {
            return Kantor::where('id', $this->kantor_id)->get();
        }
        return collect();
    }

    /**
     * Nama kantor dipisah koma.
     */
    public function getKantorNamaListAttribute(): string
    {
        return $this->all_kantors->pluck('short_name')->join(', ') ?: 'Semua Kantor';
    }

    /**
     * Cek apakah user punya akses ke kantor tertentu (by ID).
     */
    public function hasKantor(int $kantorId): bool
    {
        if ($this->peran === 'admin') return true;

        $pivotIds = $this->kantors->pluck('id')->toArray();
        if (!empty($pivotIds)) {
            return in_array($kantorId, $pivotIds);
        }

        return $this->kantor_id === $kantorId;
    }
}