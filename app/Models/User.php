<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';

    protected $guarded = [];

    // Accessor role membaca jenis_user_id (misal: 4 = petugas)
    public function getRoleAttribute()
    {
        // 1. Jika kolom role di tabel user ada isinya
        if (! empty($this->attributes['role'])) {
            return strtolower(trim($this->attributes['role']));
        }

        // 2. Pemetaan berdasarkan ID jenis_user_id
        $jenisId = $this->attributes['jenis_user_id'] ?? null;

        if ($jenisId == 4) {
            return 'petugas';
        }

        // 3. Fallback: Ambil nama dari tabel jenis_user jika ID lain
        if (! empty($jenisId)) {
            $jenis = DB::table('jenis_user')->where('id', $jenisId)->first();
            if ($jenis) {
                return strtolower(trim($jenis->nama_jenis ?? $jenis->nama ?? $jenis->jenis ?? ''));
            }
        }

        return '';
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'user_id');
    }

    public function reservasi(): HasMany
    {
        return $this->hasMany(Reservasi::class, 'user_id');
    }
}