<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipeKoleksi extends Model
{
    protected $table = 'tipe_koleksi';

    // Tabel ini tidak punya kolom created_at / updated_at.
    public $timestamps = false;

    protected $fillable = ['nama_tipe'];

    public function buku(): HasMany
    {
        return $this->hasMany(Buku::class);
    }
}
