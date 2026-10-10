<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TugasAkhir extends Model
{
    public const MENUNGGU = 'Menunggu';
    public const DISETUJUI = 'Disetujui';
    public const DITOLAK = 'Ditolak';

    protected $table = 'tugas_akhir';

<<<<<<< HEAD
    public $timestamps = false;

    protected $fillable = ['user_id', 'reviewer_id', 'judul', 'file_path', 'status', 'catatan_reviewer'];
=======
    // Tabel ini tidak punya kolom created_at / updated_at.
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'judul', 'pembimbing', 'file_tugas', 'status',
        'reviewer_id', 'catatan_reviewer',
    ];
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
