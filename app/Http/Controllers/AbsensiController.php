<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AbsensiController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $query = Absensi::with('user');

        if (in_array($user->role, ['mahasiswa', 'dosen'], true)) {
            $query->where('user_id', $user->id);
        }

        return view('absensi.index', [
            'absensi' => $query->latest('tanggal')->paginate(15),
        ]);
    }

    /**
     * Dipakai petugas untuk input absensi manual (belum pakai scan QR).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:user,id'],
        ]);

        $data = [
            'user_id' => $request->user_id,
            'tanggal' => today(),
            'waktu_masuk' => now()->format('H:i:s'),
        ];

        // DIUBAH: kolom metode hanya diisi kalau memang ada di tabel absensi
        if (Schema::hasColumn('absensi', 'metode')) {
            $data['metode'] = 'manual';
        }

        Absensi::create($data);

        return back()->with('success', 'Absensi tercatat.');
    }
}