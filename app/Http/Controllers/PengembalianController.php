<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;

class PengembalianController extends Controller
{
    public function index()
    {
        // Peminjaman yang sedang berjalan dan belum dikembalikan.
        $peminjaman = Peminjaman::with(['user', 'detail.buku'])
            ->whereIn('status', ['aktif', 'dipinjam'])
            ->whereNull('tanggal_kembali')
            ->orderBy('tanggal_pinjam')
            ->paginate(10);

        return view('pengembalian.index', compact('peminjaman'));
    }
}