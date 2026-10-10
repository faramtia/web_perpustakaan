<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peminjaman extends Model
{
<<<<<<< HEAD
    public $timestamps = false;
=======
    // Nilai status sesuai isi kolom `status` di database.
    public const MENUNGGU = 'Menunggu';         // baru diajukan, belum diverifikasi petugas
    public const DIPINJAM = 'Dipinjam';         // sudah disetujui & sedang dipinjam
    public const DIKEMBALIKAN = 'Dikembalikan';
    public const DITOLAK = 'Ditolak';

>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
    protected $table = 'peminjaman';

    // Tabel ini tidak punya kolom created_at / updated_at.
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'petugas_id', 'tanggal_pinjam',
        'tanggal_jatuh_tempo', 'tanggal_kembali', 'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_jatuh_tempo' => 'date',
            'tanggal_kembali' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function denda(): HasMany
    {
        return $this->hasMany(Denda::class);
    }

    /** Masih dipinjam dan sudah lewat jatuh tempo. */
    public function isTerlambat(): bool
    {
        return $this->status === self::DIPINJAM
            && $this->tanggal_jatuh_tempo !== null
            && $this->tanggal_jatuh_tempo->lt(today());
    }

    /** Status untuk ditampilkan: "Terlambat" kalau sudah lewat jatuh tempo. */
    public function getStatusTampilAttribute(): string
    {
        return $this->isTerlambat() ? 'Terlambat' : (string) $this->status;
    }
}
