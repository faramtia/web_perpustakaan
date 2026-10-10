<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPeminjaman extends Model
{
    public $timestamps = false;
    protected $table = 'detail_peminjaman';

    protected $fillable = ['peminjaman_id', 'buku_id', 'jumlah', 'status_kembali', 'denda'];

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class);
    }
}
