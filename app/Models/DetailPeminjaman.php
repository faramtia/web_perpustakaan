<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DetailPeminjaman extends Model
{
    public $timestamps = false;
    protected $table = 'detail_peminjaman';

    // Tabel ini tidak punya kolom created_at / updated_at.
    public $timestamps = false;

    protected $fillable = ['peminjaman_id', 'buku_id', 'jumlah'];

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class);
    }

    public function denda(): HasMany
    {
        return $this->hasMany(Denda::class, 'detail_peminjaman_id');
    }
}
