<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lokasi extends Model
{
    protected $table = 'lokasi';

    protected $fillable = ['nama_ruang'];

    public function buku(): HasMany
    {
        return $this->hasMany(Buku::class);
    }
}
