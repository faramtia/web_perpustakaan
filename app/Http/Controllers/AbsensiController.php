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
            'absensi' => $query->orderByDesc('tanggal')->orderByDesc('id')->paginate(15),
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

<<<<<<< HEAD
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
=======
        $sudahAbsen = Absensi::where('user_id', $request->user_id)
            ->whereDate('tanggal', today())
            ->exists();

        if ($sudahAbsen) {
            return back()->with('error', 'Anggota ini sudah tercatat hadir hari ini.');
        }

        Absensi::create([
            'user_id' => $request->user_id,
            'tanggal' => today(),
            'waktu_masuk' => now()->format('H:i:s'),
        ]);
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3

        return back()->with('success', 'Absensi tercatat.');
    }
}