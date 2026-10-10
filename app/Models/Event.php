<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $table = 'event';

    // Tabel ini tidak punya kolom created_at / updated_at.
    public $timestamps = false;

    protected $fillable = ['judul', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 'lokasi', 'kuota'];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    // Kolom penghubungnya bernama `events_id` (bukan `event_id`).
    public function peserta(): HasMany
    {
        return $this->hasMany(EventPeserta::class, 'events_id');
    }
}
