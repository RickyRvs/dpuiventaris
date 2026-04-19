<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mutasi extends Model
{
    protected $fillable = [
        'kode','aset_id','kantor_asal_id','kantor_tujuan_id',
        'pengaju_id','alasan','status',
    ];

    public function aset(): BelongsTo        { return $this->belongsTo(Aset::class); }
    public function kantorAsal(): BelongsTo  { return $this->belongsTo(Kantor::class, 'kantor_asal_id'); }
    public function kantorTujuan(): BelongsTo{ return $this->belongsTo(Kantor::class, 'kantor_tujuan_id'); }
    public function pengaju(): BelongsTo     { return $this->belongsTo(User::class, 'pengaju_id'); }
}