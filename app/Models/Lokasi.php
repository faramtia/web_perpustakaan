<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lokasi extends Model
{
    protected $table = 'lokasi';

    // Tabel ini tidak punya kolom created_at / updated_at.
    public $timestamps = false;

    protected $fillable = ['nama_ruang', 'keterangan'];

    public function buku(): HasMany
    {
        return $this->hasMany(Buku::class);
    }
}
