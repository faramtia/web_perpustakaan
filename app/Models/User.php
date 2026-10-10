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

<<<<<<< HEAD
    protected $table = 'user';
=======
    // Tabel di database bernama "user" (bukan "users") dan tidak punya created_at/updated_at.
    protected $table = 'user';

    public $timestamps = false;

    protected $fillable = [
        'nama', 'email', 'password', 'jenis_user_id', 'nim_nip', 'password', 'remember_token',
    ];
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3

    protected $guarded = [];

    // Accessor role membaca jenis_user_id (misal: 4 = petugas)
    public function getRoleAttribute()
    {
<<<<<<< HEAD
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

=======
        return [
            // 'hashed' otomatis meng-hash password saat disimpan.
            'password' => 'hashed',
        ];
    }

    public function jenisUser(): BelongsTo
    {
        return $this->belongsTo(JenisUser::class, 'jenis_user_id');
    }

    /**
     * Supaya semua Blade lama yang memakai $user->name tetap jalan,
     * walau kolom di database bernama "nama".
     */
    public function getNameAttribute(): ?string
    {
        return $this->nama;
    }

    /**
     * Role selalu dikembalikan huruf kecil ('admin', 'petugas', 'mahasiswa', 'dosen'),
     * karena di database tertulis 'Admin', 'Mahasiswa', 'Dosen', 'petugas' (campur).
     */
    public function getRoleAttribute(): ?string
    {
        $role = $this->jenisUser?->nama_role;

        return $role ? strtolower(trim($role)) : null;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    public function isDosen(): bool
    {
        return $this->role === 'dosen';
    }

    // --- Relasi transaksi ---
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'user_id');
    }

    public function reservasi(): HasMany
    {
        return $this->hasMany(Reservasi::class, 'user_id');
    }
<<<<<<< HEAD
}
=======

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    public function tugasAkhir(): HasMany
    {
        return $this->hasMany(TugasAkhir::class);
    }

    public function artikel(): HasMany
    {
        return $this->hasMany(Artikel::class, 'user_id');
    }
}
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
