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
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = Reservasi::with(['user.jenisUser', 'buku']);

        if ($user->isAnggota()) {
            $query->where('user_id', $user->id);
        }

        if (in_array($request->status, [Reservasi::MENUNGGU, Reservasi::DIPROSES, Reservasi::SELESAI, Reservasi::DIBATALKAN], true)) {
            $query->where('status', $request->status);
        }

        // Petugas/admin dapat mencari berdasarkan nama/NIM-NIP anggota atau judul buku.
        if ($user->isStaff() && $request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($w) use ($q) {
                $w->whereHas('user', fn ($u) => $u->where('nama', 'like', "%{$q}%")->orWhere('nim_nip', 'like', "%{$q}%"))
                    ->orWhereHas('buku', fn ($b) => $b->where('judul', 'like', "%{$q}%"));
            });
        }

        $ringkasan = $user->isStaff()
            ? Reservasi::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status')
            : collect();

        return view('reservasi.index', [
            'ringkasan' => $ringkasan,
            'reservasi' => $query->latest('id')->paginate(10)->withQueryString(),
            // Reservasi hanya untuk buku yang stoknya habis.
            'bukuHabis' => $user->isAnggota() ? Buku::where('stok', '<=', 0)->orderBy('judul')->get() : collect(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'buku_id' => ['required', 'exists:buku,id'],
        ], [
            'buku_id.required' => 'Pilih buku yang ingin direservasi.',
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        if ($buku->tersedia()) {
            return back()->with('error', 'Buku ini masih memiliki stok tersedia. Silakan ajukan peminjaman langsung.');
        }

        $sudahReservasi = Reservasi::where('user_id', Auth::id())
            ->where('buku_id', $buku->id)
            ->whereIn('status', [Reservasi::MENUNGGU, Reservasi::DIPROSES])
            ->exists();

        if ($sudahReservasi) {
            return back()->with('error', 'Kamu sudah mereservasi buku ini.');
        }

        Reservasi::create([
            'user_id' => Auth::id(),
            'buku_id' => $buku->id,
            'tanggal_reservasi' => today(),
            'status' => Reservasi::MENUNGGU,
        ]);

        return back()->with('success', 'Reservasi buku berhasil diajukan.');
    }

    /** Anggota membatalkan reservasinya sendiri (hanya selama masih Menunggu). */
    public function batalkan(Reservasi $reservasi): RedirectResponse
    {
        abort_unless($reservasi->user_id === Auth::id(), 403);

        if ($reservasi->status !== Reservasi::MENUNGGU) {
            return back()->with('error', 'Reservasi ini tidak bisa dibatalkan.');
        }

        $reservasi->update(['status' => Reservasi::DIBATALKAN]);

        return back()->with('success', 'Reservasi berhasil dibatalkan.');
    }

    /**
     * Petugas/admin memproses reservasi:
     *  Menunggu -> Diproses  (hanya jika stok buku sudah tersedia)
     *  Diproses -> Selesai   (anggota sudah mengambil/meminjam buku)
     *  Menunggu/Diproses -> Dibatalkan
     */
    public function ubahStatus(Request $request, Reservasi $reservasi): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:'.Reservasi::DIPROSES.','.Reservasi::SELESAI.','.Reservasi::DIBATALKAN],
        ]);

        $baru = $request->status;

        if (in_array($reservasi->status, [Reservasi::SELESAI, Reservasi::DIBATALKAN], true)) {
            return back()->with('error', 'Reservasi ini sudah selesai atau dibatalkan.');
        }

        if ($baru === Reservasi::DIPROSES) {
            if ($reservasi->status !== Reservasi::MENUNGGU) {
                return back()->with('error', 'Hanya reservasi berstatus Menunggu yang bisa diproses.');
            }
            if (($reservasi->buku->stok ?? 0) < 1) {
                return back()->with('error', 'Stok buku masih kosong, reservasi belum bisa diproses.');
            }
        }

        if ($baru === Reservasi::SELESAI && $reservasi->status !== Reservasi::DIPROSES) {
            return back()->with('error', 'Reservasi harus diproses dulu sebelum diselesaikan.');
        }

        $reservasi->update(['status' => $baru]);

        return back()->with('success', 'Status reservasi diperbarui menjadi '.$baru.'.');
    }
}
