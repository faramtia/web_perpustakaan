<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Reservasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReservasiController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $query = Reservasi::with(['user', 'buku']);

        if (in_array($user->role, ['mahasiswa', 'dosen'], true)) {
            $query->where('user_id', $user->id);
        }

<<<<<<< HEAD
        $reservasi = $query->orderBy('id', 'desc')->paginate(10);

        // Hanya tampilkan buku yang stoknya habis/kosong (stok = 0) untuk reservasi
        $bukuKosong = Buku::where('stok', '<=', 0)->orderBy('judul')->get();

        return view('reservasi.index', compact('reservasi', 'bukuKosong'));
=======
        return view('reservasi.index', [
            'reservasi' => $query->latest('id')->paginate(10),
            'bukuHabis' => Buku::where('stok', 0)->orderBy('judul')->get(),
        ]);
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'buku_id' => ['required', 'exists:buku,id'],
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        if ($buku->stok > 0) {
            return back()->with('error', 'Buku ini masih memiliki stok tersedia. Silakan ajukan peminjaman langsung.');
        }

        // Cek apakah user sudah pernah melakukan reservasi buku yang sama dan masih aktif/menunggu
        $sudahReservasi = Reservasi::where('user_id', Auth::id())
            ->where('buku_id', $buku->id)
            ->whereIn('status', ['menunggu', 'pending', 'aktif'])
            ->exists();

        if ($sudahReservasi) {
            return back()->with('error', 'Anda sudah melakukan reservasi untuk buku ini.');
        }

        Reservasi::create([
            'user_id' => Auth::id(),
            'buku_id' => $buku->id,
            'status' => 'Menunggu',
            'tanggal_reservasi' => today(),
<<<<<<< HEAD
=======
            'status' => Reservasi::MENUNGGU,
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
        ]);

        return back()->with('success', 'Reservasi buku berhasil diajukan.');
    }

    public function batalkan(Reservasi $reservasi): RedirectResponse
    {
<<<<<<< HEAD
        if ($reservasi->user_id !== Auth::id() && ! in_array(Auth::user()->role, ['admin', 'petugas'], true)) {
            return back()->with('error', 'Anda tidak memiliki akses untuk membatalkan reservasi ini.');
        }
=======
        // Anggota hanya boleh membatalkan reservasi miliknya sendiri.
        abort_unless($reservasi->user_id === Auth::id(), 403);

        if ($reservasi->status !== Reservasi::MENUNGGU) {
            return back()->with('error', 'Reservasi ini tidak bisa dibatalkan.');
        }

        $reservasi->update(['status' => Reservasi::DIBATALKAN]);
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3

        $reservasi->update([
            'status' => 'Dibatalkan',
        ]);

        return back()->with('success', 'Reservasi berhasil dibatalkan.');
    }
}