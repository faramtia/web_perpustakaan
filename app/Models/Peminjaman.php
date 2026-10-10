<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peminjaman extends Model
{
    public $timestamps = false;
    protected $table = 'peminjaman';

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
}
