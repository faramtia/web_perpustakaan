<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'kategori';

    // Tabel ini tidak punya kolom created_at / updated_at.
    public $timestamps = false;

    protected $fillable = ['nama_kategori'];

    public function buku(): HasMany
    {
        return $this->hasMany(Buku::class);
    }

    public function ejurnal(): HasMany
    {
        return $this->hasMany(Ejurnal::class);
    }
}
