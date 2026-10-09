<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservasi extends Model
{
    public const MENUNGGU = 'Menunggu';
    public const DIPROSES = 'Diproses';
    public const SELESAI = 'Selesai';
    public const DIBATALKAN = 'Dibatalkan';

    protected $table = 'reservasi';

    // Tabel ini tidak punya kolom created_at / updated_at.
    public $timestamps = false;

    protected $fillable = ['user_id', 'buku_id', 'tanggal_reservasi', 'status'];

    protected function casts(): array
    {
        return [
            'tanggal_reservasi' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class);
    }
}
