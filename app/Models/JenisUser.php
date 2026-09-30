<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisUser extends Model
{
    use HasFactory;

    protected $table = 'jenis_user';

    protected $fillable = ['nama_role', 'deskripsi'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
