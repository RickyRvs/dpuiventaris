<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BeritaAcara extends Model
{
    use HasFactory;

    protected $table = 'berita_acaras';

    protected $fillable = [
        'nomor',
        'aset_id',       // nullable, legacy / opsional
        'kantor_id',
        'pihak_pertama_nama',
        'pihak_pertama_jabatan',
        'pihak_kedua_nama',
        'pihak_kedua_jabatan',
        'tanggal_serah_terima',
        'keterangan',
        // Snapshot aset PERTAMA (untuk backward compat & label ringkas)
        'aset_nama',
        'aset_kode',
        'aset_kategori',
        'aset_kondisi',
        'aset_nilai',
        'status',
        'dokumen_signed_path',
        'dokumen_signed_nama',
        'uploaded_at',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_serah_terima' => 'date',
        'uploaded_at'          => 'datetime',
        'aset_nilai'           => 'float',
    ];

    // ── Relationships ───────────────────────────────────────────

    /** Relasi legacy (single aset) — tetap ada untuk backward compat */
    public function aset()
    {
        return $this->belongsTo(Aset::class, 'aset_id');
    }

    /** Relasi baru: many-to-many dengan snapshot di pivot */
    public function asets()
    {
        return $this->belongsToMany(
            Aset::class,
            'berita_acara_aset',
            'berita_acara_id',
            'aset_id'
        )->withPivot([
            'aset_nama',
            'aset_kode',
            'aset_kategori',
            'aset_kondisi',
            'aset_nilai',
        ])->withTimestamps();
    }

    public function kantor()
    {
        return $this->belongsTo(Kantor::class, 'kantor_id');
    }

    // ── Accessors ───────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'               => 'Draft',
            'template_downloaded' => 'Template Diunduh',
            'menunggu_upload'     => 'Menunggu Upload TTD',
            'selesai'             => 'Selesai',
            default               => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft'               => '#64748b',
            'template_downloaded' => '#ca8a04',
            'menunggu_upload'     => '#2563eb',
            'selesai'             => '#16a34a',
            default               => '#64748b',
        };
    }

    public function getStatusBgAttribute(): string
    {
        return match ($this->status) {
            'draft'               => '#f1f5f9',
            'template_downloaded' => '#fef9c3',
            'menunggu_upload'     => '#dbeafe',
            'selesai'             => '#dcfce7',
            default               => '#f1f5f9',
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            'draft'               => 'draft',
            'template_downloaded' => 'download',
            'menunggu_upload'     => 'upload_file',
            'selesai'             => 'task_alt',
            default               => 'help',
        };
    }

    public function getNilaiFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->aset_nilai, 0, ',', '.');
    }

    /**
     * Total nilai semua aset dalam BA ini (dari pivot).
     */
    public function getTotalNilaiAttribute(): float
    {
        return $this->asets->sum(fn($a) => $a->pivot->aset_nilai ?? 0);
    }

    public function getTotalNilaiFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->total_nilai, 0, ',', '.');
    }

    // ── Static Helpers ──────────────────────────────────────────

    public static function generateNomor(): string
    {
        $prefix = 'BA-' . now()->format('Ym') . '-';
        $last   = static::where('nomor', 'like', $prefix . '%')
                        ->orderByDesc('id')
                        ->first();

        $seq = $last
            ? ((int) Str::afterLast($last->nomor, '-')) + 1
            : 1;

        return $prefix . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }
}