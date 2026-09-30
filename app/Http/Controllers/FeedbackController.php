<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return view('feedback.index', ['feedback' => $query->latest()->paginate(10)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'jenis' => ['required', 'in:keluhan,saran,tanya_pustakawan'],
            'isi' => ['required', 'string'],
        ]);

        Feedback::create([
            ...$data,
            'user_id' => Auth::id(),
            'status' => 'baru',
        ]);

        return back()->with('success', 'Terkirim! Petugas akan segera merespons.');
    }

    /**
     * Petugas/admin balas & ubah status.
     */
    public function balas(Request $request, Feedback $feedback): RedirectResponse
    {
        $request->validate(['balasan' => ['required', 'string']]);

        $feedback->update([
            'balasan' => $request->balasan,
            'status' => 'selesai',
        ]);

        return back()->with('success', 'Balasan terkirim.');
    }
}
