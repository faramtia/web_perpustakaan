<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ejurnal extends Model
{
    protected $table = 'ejurnal';

    protected $fillable = ['kategori_id', 'judul', 'penulis', 'abstrak', 'tahun', 'lokasi_rak'];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }
}
