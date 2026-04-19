<?php
// ============================================================
//  app/Models/Stok.php
// ============================================================
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stok extends Model
{
    protected $fillable = ['kode','nama','satuan','stok','min_stok','kategori','kantor_id'];

    public function kantor(): BelongsTo { return $this->belongsTo(Kantor::class); }

    public function getStatusAttribute(): string
    {
        if ($this->stok == 0) return 'Habis';
        if ($this->stok < $this->min_stok) return 'Kritis';
        return 'Aman';
    }

    public function getPctAttribute(): int
    {
        if ($this->min_stok == 0) return 100;
        return min(100, (int) round($this->stok / $this->min_stok * 100));
    }
}