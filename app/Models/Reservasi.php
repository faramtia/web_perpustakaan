<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservasi extends Model
{
    protected $table = 'reservasi';

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
