<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ejurnal extends Model
{
    public $timestamps = false; // tabel ejurnal tidak punya created_at / updated_at

    protected $table = 'ejurnal';

<<<<<<< HEAD
    // Kolom tahun di database = tahun_terbit
    protected $fillable = ['kategori_id', 'judul', 'penulis', 'abstrak', 'tahun_terbit'];

    // Supaya view lama yang memakai $item->tahun tetap jalan
    public function getTahunAttribute()
    {
        return $this->attributes['tahun_terbit'] ?? null;
    }
=======
    // Tabel ini tidak punya kolom created_at / updated_at.
    public $timestamps = false;

    protected $fillable = ['kategori_id', 'judul', 'penulis', 'abstrak', 'tahun_terbit', 'file_jurnal'];
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }
}