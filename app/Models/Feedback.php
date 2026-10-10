<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    public const BELUM_DIBALAS = 'Belum Dibalas';
    public const SUDAH_DIBALAS = 'Sudah Dibalas';

    protected $table = 'feedback';

    // Tabel ini tidak punya kolom created_at / updated_at.
    public $timestamps = false;

    protected $fillable = ['user_id', 'jenis', 'isi', 'balasan', 'status', 'tanggal'];

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
