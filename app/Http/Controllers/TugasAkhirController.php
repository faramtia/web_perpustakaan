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
    public function index(): View
    {
        $user = Auth::user();

        $query = TugasAkhir::with(['user', 'reviewer']);

        if (in_array($user->role, ['mahasiswa', 'dosen'], true)) {
            $query->where('user_id', $user->id);
        }

        return view('tugas-akhir.index', ['tugasAkhir' => $query->orderBy('id', 'desc')
        ->paginate(10)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $path = $request->file('file')->store('tugas-akhir', 'public');

        TugasAkhir::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'file_tugas' => $path,
            'status' => 'diajukan',
        ]);

        return back()->with('success', 'Tugas akhir terkirim, menunggu review petugas.');
    }

    /**
     * Petugas/admin review upload TA.
     */
    public function review(Request $request, TugasAkhir $tugasAkhir): RedirectResponse
    {
        $request->validate([
        'keputusan' => ['required', 'in:disetujui,ditolak'],
        ]);

        $tugasAkhir->update(['status' => $request->keputusan]);

        return back()->with('success', 'Review tersimpan.');
            

        

        return back()->with('success', 'Review tersimpan.');
    }
}
