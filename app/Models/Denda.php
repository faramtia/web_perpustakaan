<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Denda extends Model
{
    public const TERLAMBAT = 'terlambat';
    public const RUSAK = 'rusak';
    public const HILANG = 'hilang';

    public const BELUM_LUNAS = 'Belum Lunas';
    public const LUNAS = 'Lunas';

    // Tabel denda hanya punya created_at (tanpa updated_at).
    public const UPDATED_AT = null;

    protected $table = 'denda';

    protected $fillable = [
        'peminjaman_id', 'detail_peminjaman_id', 'jenis', 'hari_terlambat',
        'tarif_per_hari', 'jumlah_denda', 'status_bayar', 'tanggal_bayar', 'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'date',
        ];
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function detail(): BelongsTo
    {
        return $this->belongsTo(DetailPeminjaman::class, 'detail_peminjaman_id');
    }
}
