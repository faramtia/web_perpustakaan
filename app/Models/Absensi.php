<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    public $timestamps = false; // DIUBAH: tabel absensi tidak punya created_at / updated_at

    protected $table = 'absensi';

    protected $fillable = ['user_id', 'tanggal', 'waktu_masuk', 'waktu_keluar', 'metode'];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}