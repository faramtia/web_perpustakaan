<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Tabel di database bernama "user" (bukan "users") dan tidak punya created_at/updated_at.
    protected $table = 'user';

    public $timestamps = false;

    protected $fillable = [
        'jenis_user_id', 'nama', 'nim_nip', 'email', 'password', 'remember_token',
    ];

    protected $hidden = ['password', 'remember_token'];

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

    /** Supaya Blade yang memakai $user->name tetap jalan, walau kolomnya bernama "nama". */
    public function getNameAttribute(): ?string
    {
        return $this->nama;
    }

    /**
     * Role selalu huruf kecil ('admin', 'petugas', 'mahasiswa', 'dosen'),
     * karena penulisan di tabel jenis_user bisa campur ('Admin', 'Petugas', ...).
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

    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'petugas'], true);
    }

    public function isAnggota(): bool
    {
        return in_array($this->role, ['mahasiswa', 'dosen'], true);
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'user_id');
    }

    public function reservasi(): HasMany
    {
        return $this->hasMany(Reservasi::class, 'user_id');
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
