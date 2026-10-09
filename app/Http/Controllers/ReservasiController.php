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

        return view('reservasi.index', [
            'reservasi' => $query->latest('id')->paginate(10),
            'bukuHabis' => Buku::where('stok', 0)->orderBy('judul')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['buku_id' => ['required', 'exists:buku,id']]);

        Reservasi::create([
            'user_id' => Auth::id(),
            'buku_id' => $request->buku_id,
            'tanggal_reservasi' => today(),
            'status' => Reservasi::MENUNGGU,
        ]);

        return back()->with('success', 'Reservasi dibuat. Kamu akan diberi tahu saat buku tersedia.');
    }

    public function batalkan(Reservasi $reservasi): RedirectResponse
    {
        // Anggota hanya boleh membatalkan reservasi miliknya sendiri.
        abort_unless($reservasi->user_id === Auth::id(), 403);

        if ($reservasi->status !== Reservasi::MENUNGGU) {
            return back()->with('error', 'Reservasi ini tidak bisa dibatalkan.');
        }

        $reservasi->update(['status' => Reservasi::DIBATALKAN]);

        return back()->with('success', 'Reservasi dibatalkan.');
    }
}
