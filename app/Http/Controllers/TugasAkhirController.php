<?php

namespace App\Http\Controllers;

use App\Models\TugasAkhir;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TugasAkhirController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $query = TugasAkhir::with(['user', 'reviewer']);

        if (in_array($user->role, ['mahasiswa', 'dosen'], true)) {
            $query->where('user_id', $user->id);
        }

        return view('tugas-akhir.index', ['tugasAkhir' => $query->latest('id')->paginate(10)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'judul' => ['required', 'string', 'max:250'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $path = $request->file('file')->store('tugas-akhir', 'public');

        TugasAkhir::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'file_tugas' => $path,
            'status' => TugasAkhir::MENUNGGU,
        ]);

        return back()->with('success', 'Tugas akhir terkirim, menunggu review petugas.');
    }

    /**
     * Petugas/admin review upload TA.
     */
    public function review(Request $request, TugasAkhir $tugasAkhir): RedirectResponse
    {
        $request->validate([
            'keputusan' => ['required', 'in:'.TugasAkhir::DISETUJUI.','.TugasAkhir::DITOLAK],
            'catatan_reviewer' => ['nullable', 'string'],
        ]);

        $tugasAkhir->update([
            'status' => $request->keputusan,
            'catatan_reviewer' => $request->catatan_reviewer,
            'reviewer_id' => Auth::id(),
        ]);

        return back()->with('success', 'Review tersimpan.');
    }
}
