<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

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
        'user_id', 'peminjaman_id', 'detail_peminjaman_id', 'jenis', 'hari_terlambat',
        'tarif_per_hari', 'jumlah_denda', 'status_bayar', 'tanggal_bayar', 'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'date',
        ];
    }

    /**
     * Membuat denda. Beberapa database punya kolom `user_id` di tabel denda (NOT NULL),
     * yang lain tidak — jadi kolom itu hanya diisi kalau memang ada, diambil dari pemilik peminjaman.
     */
    public static function buat(Peminjaman $peminjaman, array $data): self
    {
        static $punyaUserId = null;
        $punyaUserId ??= Schema::hasColumn('denda', 'user_id');

        if ($punyaUserId) {
            $data['user_id'] = $peminjaman->user_id;
        }

        return static::create(['peminjaman_id' => $peminjaman->id] + $data);
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
