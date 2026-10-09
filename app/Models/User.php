<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Tabel di database bernama "user" (bukan "users") dan tidak punya created_at/updated_at.
    protected $table = 'user';

    public $timestamps = false;

    protected $fillable = [
        'nama', 'email', 'password', 'jenis_user_id', 'nim_nip', 'password', 'remember_token',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
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
    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function reservasi(): HasMany
    {
        return $this->hasMany(Reservasi::class);
    }

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
