<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama', 'email', 'password',
        'peran', 'kantor_id',
        'initials', 'color1', 'color2',
        'last_login_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'last_login_at' => 'datetime',
        'password'      => 'hashed',
    ];

    public function kantor(): BelongsTo
    {
        return $this->belongsTo(Kantor::class);
    }

    public function mutasis(): HasMany
    {
        return $this->hasMany(Mutasi::class, 'pengaju_id');
    }

    public function getBergabungAttribute(): string
    {
        return $this->created_at?->translatedFormat('M Y') ?? '-';
    }

    public function getLastLoginFormattedAttribute(): string
    {
        return $this->last_login_at?->translatedFormat('d M Y, H:i') ?? '-';
    }

    public function getKantorNamaAttribute(): string
    {
        return $this->peran === 'admin'
            ? 'Semua Kantor'
            : ($this->kantor?->short_name ?? '-');
    }
}