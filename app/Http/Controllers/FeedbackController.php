<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $query = Feedback::with('user');

        if (in_array($user->role, ['mahasiswa', 'dosen'], true)) {
            $query->where('user_id', $user->id);
        }

<<<<<<< HEAD
        return view('feedback.index', [
            'feedback' => $query->orderBy('id', 'desc')->paginate(10),
        ]);
=======
        return view('feedback.index', ['feedback' => $query->latest('id')->paginate(10)]);
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'jenis' => ['required', 'in:keluhan,saran,tanya_pustakawan'],
            'isi' => ['required', 'string'],
        ]);

        $feedback = new Feedback([
            ...$data,
            'user_id' => Auth::id(),
            'tanggal' => today(),
            'status' => Feedback::BELUM_DIBALAS,
        ]);
        $feedback->timestamps = $this->punyaTimestamps(); // DIUBAH
        $feedback->save();

        return back()->with('success', 'Terkirim! Petugas akan segera merespons.');
    }

    /**
     * Petugas/admin balas & ubah status.
     */
    public function balas(Request $request, Feedback $feedback): RedirectResponse
    {
        $request->validate(['balasan' => ['required', 'string']]);

        $feedback->timestamps = $this->punyaTimestamps(); // DIUBAH: tidak error kalau tabel tanpa updated_at
        $feedback->update([
            'balasan' => $request->balasan,
            'status' => Feedback::SUDAH_DIBALAS,
        ]);

        return back()->with('success', 'Balasan terkirim.');
    }

    private function punyaTimestamps(): bool
    {
        return Schema::hasColumn('feedback', 'created_at') && Schema::hasColumn('feedback', 'updated_at');
    }
}