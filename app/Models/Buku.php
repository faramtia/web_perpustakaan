<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buku extends Model
{
    protected $table = 'buku';

    protected $fillable = [
        'kategori_id', 'lokasi_id', 'tipe_koleksi_id',
        'judul', 'penulis', 'penerbit', 'tahun_terbit', 'isbn', 'stok', 'cover',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function tipeKoleksi(): BelongsTo
    {
        return $this->belongsTo(TipeKoleksi::class);
    }

    public function detailPeminjaman(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function reservasi(): HasMany
    {
        return $this->hasMany(Reservasi::class);
    }

    public function tersedia(): bool
    {
        return $this->stok > 0;
    }
}
