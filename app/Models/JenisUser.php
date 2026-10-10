<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisUser extends Model
{
    protected $table = 'jenis_user';
<<<<<<< HEAD
    protected $guarded = [];
}
=======

    // Tabel jenis_user tidak punya created_at/updated_at.
    // public $timestamps = false;

    protected $fillable = ['nama_role', 'lama_pinjam_hari', 'denda_per_hari'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'jenis_user_id');
    }
}
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
