<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TugasAkhir extends Model
{
    protected $table = 'tugas_akhir';

    protected $fillable = ['user_id', 'reviewer_id', 'judul', 'file_path', 'status', 'catatan_reviewer'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
