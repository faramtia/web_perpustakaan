<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisUser extends Model
{
    use HasFactory;

    protected $table = 'jenis_user';

    // Tabel jenis_user tidak punya created_at/updated_at.
    // public $timestamps = false;

    protected $fillable = ['nama_role', 'lama_pinjam_hari', 'denda_per_hari'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'jenis_user_id');
    }
}
