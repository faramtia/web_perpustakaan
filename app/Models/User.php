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

    protected $fillable = [
        'name', 'email', 'password', 'jenis_user_id', 'nim_nip',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function jenisUser(): BelongsTo
    {
        return $this->belongsTo(JenisUser::class);
    }

    /**
     * Accessor supaya kode Blade lama yang pakai $user->role tetap jalan,
     * walau datanya sekarang diambil lewat relasi jenis_user.
     */
    public function getRoleAttribute(): ?string
    {
        return $this->jenisUser?->nama_role;
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
        return $this->hasMany(Artikel::class, 'penulis_id');
    }
}
