<?php
// ================================================================
//  app/Models/Kantor.php
// ================================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kantor extends Model
{
    protected $fillable = ['kode', 'nama', 'short_name'];

    public function users(): HasMany   { return $this->hasMany(User::class); }
    public function asets(): HasMany   { return $this->hasMany(Aset::class); }
    public function stoks(): HasMany   { return $this->hasMany(Stok::class); }
    public function jadwals(): HasMany { return $this->hasMany(Jadwal::class); }
}