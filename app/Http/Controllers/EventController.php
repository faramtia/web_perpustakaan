<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        return view('event.index', [
            'event' => Event::withCount('peserta')->orderBy('tanggal_mulai')->paginate(10),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date'],
            'lokasi' => ['nullable', 'string', 'max:150'],
            'kuota' => ['required', 'integer', 'min:0'],
        ]);

        Event::create($data);

        return back()->with('success', 'Event ditambahkan.');
    }

    /**
     * Anggota daftar ke sebuah event.
     */
    public function daftar(Event $event): RedirectResponse
    {
        $sudahTerdaftar = $event->peserta()->where('user_id', Auth::id())->exists();

        if ($sudahTerdaftar) {
            return back()->with('error', 'Kamu sudah terdaftar di event ini.');
        }

        if ($event->kuota > 0 && $event->peserta()->count() >= $event->kuota) {
            return back()->with('error', 'Kuota event sudah penuh.');
        }

        $event->peserta()->create([
            'user_id' => Auth::id(),
            'status_pendaftaran' => 'diterima',
            'tanggal_daftar' => now(),
        ]);

        return back()->with('success', 'Berhasil daftar event!');
    }
}
