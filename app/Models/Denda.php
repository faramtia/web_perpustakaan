<?php

<<<<<<< HEAD
namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DendaController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        // Ambil peminjaman tanpa klausa WHERE denda agar tidak terjadi SQL Column Not Found
        $query = Peminjaman::with(['user']);

        if (in_array($user->role, ['mahasiswa', 'dosen'], true)) {
            $query->where('user_id', $user->id);
        }

        $dendaList = $query->orderBy('id', 'desc')->paginate(10);

        return view('denda.index', compact('dendaList'));
    }

    public function bayar(Request $request, $id): RedirectResponse
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Jika ada kolom status_denda atau status, update nilainya
        $peminjaman->update([
            'status_denda' => 'lunas',
        ]);

        return back()->with('success', 'Pembayaran denda berhasil dicatat.');
    }
}
=======
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Denda extends Model
{
    public const TERLAMBAT = 'terlambat';
    public const RUSAK = 'rusak';
    public const HILANG = 'hilang';

    public const BELUM_LUNAS = 'Belum Lunas';
    public const LUNAS = 'Lunas';

    // Tabel denda hanya punya created_at (tanpa updated_at).
    public const UPDATED_AT = null;

    protected $table = 'denda';

    protected $fillable = [
        'peminjaman_id', 'detail_peminjaman_id', 'jenis', 'hari_terlambat',
        'tarif_per_hari', 'jumlah_denda', 'status_bayar', 'tanggal_bayar', 'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'date',
        ];
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function detail(): BelongsTo
    {
        return $this->belongsTo(DetailPeminjaman::class, 'detail_peminjaman_id');
    }
}
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
