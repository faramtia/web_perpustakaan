<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventPeserta extends Model
{
    protected $table = 'event_peserta';

    protected $fillable = ['event_id', 'user_id', 'status_pendaftaran', 'tanggal_daftar'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
