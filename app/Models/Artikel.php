<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Artikel extends Model
{
    protected $table = 'artikel';

    // Tabel ini tidak punya kolom created_at / updated_at.
    public $timestamps = false;

    protected $fillable = ['user_id', 'kategori_id', 'judul', 'isi_artikel', 'tanggal_upload'];

    protected function casts(): array
    {
        return [
            'tanggal_upload' => 'date',
        ];
    }

    public function penulis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }
}
