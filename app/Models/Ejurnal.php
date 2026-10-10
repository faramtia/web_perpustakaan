<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ejurnal extends Model
{
    public $timestamps = false; // tabel ejurnal tidak punya created_at / updated_at

    protected $table = 'ejurnal';

    // Kolom tahun di database = tahun_terbit
    protected $fillable = ['kategori_id', 'judul', 'penulis', 'abstrak', 'tahun_terbit'];

    // Supaya view lama yang memakai $item->tahun tetap jalan
    public function getTahunAttribute()
    {
        return $this->attributes['tahun_terbit'] ?? null;
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }
}