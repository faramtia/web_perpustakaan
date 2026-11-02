<?php

namespace App\Http\Controllers;

use App\Models\TugasAkhir;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TugasAkhirController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = TugasAkhir::with(['user.jenisUser', 'reviewer']);

        if ($user->isAnggota()) {
            $query->where('user_id', $user->id);
        }

        if (in_array($request->status, [TugasAkhir::MENUNGGU, TugasAkhir::DISETUJUI, TugasAkhir::DITOLAK], true)) {
            $query->where('status', $request->status);
        }

        if ($user->isStaff() && $request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($w) use ($q) {
                $w->where('judul', 'like', "%{$q}%")
                    ->orWhereHas('user', fn ($u) => $u->where('nama', 'like', "%{$q}%"));
            });
        }

        $hitung = $user->isAnggota() ? TugasAkhir::where('user_id', $user->id) : TugasAkhir::query();

        return view('tugas-akhir.index', [
            'tugasAkhir' => $query->latest('id')->paginate(10)->withQueryString(),
            'ringkasan' => [
                TugasAkhir::MENUNGGU => (clone $hitung)->where('status', TugasAkhir::MENUNGGU)->count(),
                TugasAkhir::DISETUJUI => (clone $hitung)->where('status', TugasAkhir::DISETUJUI)->count(),
                TugasAkhir::DITOLAK => (clone $hitung)->where('status', TugasAkhir::DITOLAK)->count(),
            ],
            // Berkas hanya ditautkan kalau benar-benar ada di storage (butuh `php artisan storage:link`).
            'adaBerkas' => fn (?string $path) => $path && Storage::disk('public')->exists($path),
        ]);
    }

    /** Koleksi tugas akhir yang sudah disetujui (publik; PDF hanya untuk yang login). */
    public function publik(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $koleksi = TugasAkhir::with('user')
            ->where('status', TugasAkhir::DISETUJUI)
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('judul', 'like', "%{$q}%")
                    ->orWhere('pembimbing', 'like', "%{$q}%")
                    ->orWhereHas('user', fn ($u) => $u->where('nama', 'like', "%{$q}%"));
            }))
            ->latest('id')
            ->paginate(9)
            ->withQueryString();

        return view('tugas-akhir.publik', [
            'koleksi' => $koleksi,
            'adaBerkas' => fn (?string $path) => $path && Storage::disk('public')->exists($path),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'judul' => ['required', 'string', 'max:250'],
            'pembimbing' => ['nullable', 'string', 'max:150'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ], [
            'judul.required' => 'Judul tugas akhir wajib diisi.',
            'file.required' => 'Pilih berkas PDF tugas akhir.',
            'file.mimes' => 'Berkas harus berformat PDF.',
            'file.max' => 'Ukuran berkas maksimal 10 MB.',
        ]);

        $path = $request->file('file')->store('tugas-akhir', 'public');

        TugasAkhir::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'pembimbing' => $request->pembimbing,
            'file_tugas' => $path,
            'status' => TugasAkhir::MENUNGGU,
        ]);

        return back()->with('success', 'Tugas akhir terkirim, menunggu review petugas.');
    }

    /** Petugas/admin mereview upload tugas akhir. */
    public function review(Request $request, TugasAkhir $tugasAkhir): RedirectResponse
    {
        $request->validate([
            'keputusan' => ['required', 'in:'.TugasAkhir::DISETUJUI.','.TugasAkhir::DITOLAK],
            'catatan_reviewer' => ['nullable', 'string', 'max:1000'],
        ]);

        $tugasAkhir->update([
            'status' => $request->keputusan,
            'reviewer_id' => Auth::id(),
            'catatan_reviewer' => $request->catatan_reviewer,
        ]);

        return back()->with('success', 'Review tersimpan: '.$request->keputusan.'.');
    }
}
