<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public const JENIS = [
        'tanya_pustakawan' => 'Tanya Pustakawan',
        'saran' => 'Saran',
        'keluhan' => 'Keluhan',
    ];

    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = Feedback::with('user.jenisUser');

        if ($user->isAnggota()) {
            $query->where('user_id', $user->id);
        }

        if (in_array($request->status, [Feedback::BELUM_DIBALAS, Feedback::SUDAH_DIBALAS], true)) {
            $query->where('status', $request->status);
        }

        return view('feedback.index', [
            'feedback' => $query->latest('id')->paginate(10)->withQueryString(),
            'jenisList' => self::JENIS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'jenis' => ['required', 'in:'.implode(',', array_keys(self::JENIS))],
            'isi' => ['required', 'string', 'max:2000'],
        ], [
            'jenis.required' => 'Pilih jenis pesan.',
            'isi.required' => 'Isi pesan wajib diisi.',
        ]);

        Feedback::create([
            ...$data,
            'user_id' => Auth::id(),
            'tanggal' => today(),
            'status' => Feedback::BELUM_DIBALAS,
        ]);

        return back()->with('success', 'Terkirim! Petugas akan segera merespons.');
    }

    /** Petugas/admin membalas & mengubah status. */
    public function balas(Request $request, Feedback $feedback): RedirectResponse
    {
        $request->validate(['balasan' => ['required', 'string', 'max:2000']], [
            'balasan.required' => 'Isi balasan wajib diisi.',
        ]);

        $feedback->update([
            'balasan' => $request->balasan,
            'status' => Feedback::SUDAH_DIBALAS,
        ]);

        return back()->with('success', 'Balasan terkirim.');
    }
}
