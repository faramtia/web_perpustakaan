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

<<<<<<< HEAD
        return view('tugas-akhir.index', ['tugasAkhir' => $query->orderBy('id', 'desc')
        ->paginate(10)]);
=======
        return view('tugas-akhir.index', ['tugasAkhir' => $query->latest('id')->paginate(10)]);
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
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
<<<<<<< HEAD
            'status' => 'diajukan',
=======
            'status' => TugasAkhir::MENUNGGU,
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
        ]);

        return back()->with('success', 'Tugas akhir terkirim, menunggu review petugas.');
    }

    /**
     * Petugas/admin review upload TA.
     */
    public function review(Request $request, TugasAkhir $tugasAkhir): RedirectResponse
    {
        $request->validate([
<<<<<<< HEAD
        'keputusan' => ['required', 'in:disetujui,ditolak'],
=======
            'keputusan' => ['required', 'in:'.TugasAkhir::DISETUJUI.','.TugasAkhir::DITOLAK],
            'catatan_reviewer' => ['nullable', 'string'],
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
        ]);

        $tugasAkhir->update(['status' => $request->keputusan]);

        return back()->with('success', 'Review tersimpan.');
            

        

        return back()->with('success', 'Review tersimpan.');
    }
}
