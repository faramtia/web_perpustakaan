<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventPeserta extends Model
{
    public const TERDAFTAR = 'Terdaftar';

    protected $table = 'event_peserta';

    // Tabel ini tidak punya kolom created_at / updated_at.
    public $timestamps = false;

    protected $fillable = ['events_id', 'user_id', 'status_pendaftaran', 'tanggal_daftar'];

    protected function casts(): array
    {
        return [
            'tanggal_daftar' => 'date',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'events_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
