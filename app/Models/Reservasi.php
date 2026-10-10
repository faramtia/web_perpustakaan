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

<<<<<<< HEAD
=======
    // Tabel ini tidak punya kolom created_at / updated_at.
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
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
