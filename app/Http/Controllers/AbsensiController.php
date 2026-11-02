<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AbsensiController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = Absensi::with('user.jenisUser');

        if ($user->isAnggota()) {
            $query->where('user_id', $user->id);
        } else {
            $query
                ->when($request->filled('tanggal'), fn ($q) => $q->whereDate('tanggal', $request->tanggal))
                ->when($request->filled('q'), function ($q) use ($request) {
                    $kata = trim($request->q);
                    $q->whereHas('user', fn ($u) => $u->where('nama', 'like', "%{$kata}%")->orWhere('nim_nip', 'like', "%{$kata}%"));
                });
        }

        return view('absensi.index', [
            'absensi' => $query->orderByDesc('tanggal')->orderByDesc('id')->paginate(15)->withQueryString(),
            'hariIni' => Absensi::whereDate('tanggal', today())->count(),
        ]);
    }

    /**
     * Petugas mencatat kehadiran manual lewat NIM/NIP anggota
     * (versi awal; nanti bisa diganti scan QR kartu anggota).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nim_nip' => ['required', 'string', 'exists:user,nim_nip'],
        ], [
            'nim_nip.required' => 'NIM / NIP wajib diisi.',
            'nim_nip.exists' => 'NIM / NIP tidak ditemukan di data pengguna.',
        ]);

        $anggota = User::where('nim_nip', $request->nim_nip)->firstOrFail();

        $sudahAbsen = Absensi::where('user_id', $anggota->id)
            ->whereDate('tanggal', today())
            ->exists();

        if ($sudahAbsen) {
            return back()->with('error', "{$anggota->nama} sudah tercatat hadir hari ini.");
        }

        Absensi::create([
            'user_id' => $anggota->id,
            'tanggal' => today(),
            'waktu_masuk' => now()->format('H:i:s'),
        ]);

        return back()->with('success', "Kehadiran {$anggota->nama} tercatat.");
    }

    /** Halaman kiosk: anggota mengetik NIM/NIP di satu komputer perpustakaan. */
    public function kiosk(): View
    {
        return view('absensi.kiosk');
    }

    public function kioskStore(Request $request): JsonResponse
    {
        $request->validate(['nim_nip' => ['required', 'string', 'max:50']]);

        $user = User::with('jenisUser')->where('nim_nip', trim($request->nim_nip))->first();

        if (! $user || ! $user->isAnggota()) {
            return response()->json(['status' => 'gagal', 'pesan' => 'NIM/NIP tidak terdaftar sebagai anggota.', 'nama' => null], 422);
        }

        if (Absensi::where('user_id', $user->id)->whereDate('tanggal', today())->exists()) {
            return response()->json(['status' => 'sudah', 'pesan' => 'Sudah tercatat hadir hari ini.', 'nama' => $user->nama]);
        }

        Absensi::create([
            'user_id' => $user->id,
            'tanggal' => today(),
            'waktu_masuk' => now()->format('H:i:s'),
        ]);

        return response()->json(['status' => 'ok', 'pesan' => 'Absensi tercatat. Selamat datang!', 'nama' => $user->nama]);
    }
}
