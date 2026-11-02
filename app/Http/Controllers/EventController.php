<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventPeserta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $event = Event::withCount('peserta')
            ->when($request->waktu === 'lalu', fn ($q) => $q->whereDate('tanggal_mulai', '<', today())->orderByDesc('tanggal_mulai'))
            ->when($request->waktu !== 'lalu', fn ($q) => $q->whereDate('tanggal_mulai', '>=', today())->orderBy('tanggal_mulai'))
            ->paginate(9)
            ->withQueryString();

        return view('event.index', [
            'event' => $event,
            'terdaftar' => Auth::check()
                ? EventPeserta::where('user_id', Auth::id())->pluck('events_id')->all()
                : [],
        ]);
    }

    public function show(Event $event): View
    {
        $event->loadCount('peserta');

        $user = Auth::user();

        return view('event.show', [
            'event' => $event,
            'sudahDaftar' => $user ? $event->peserta()->where('user_id', $user->id)->exists() : false,
            'peserta' => $user && $user->isStaff()
                ? $event->peserta()->with('user.jenisUser')->get()
                : collect(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'lokasi' => ['required', 'string', 'max:100'],
            'kuota' => ['nullable', 'integer', 'min:0'],
        ], [
            'judul.required' => 'Judul event wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'lokasi.required' => 'Lokasi event wajib diisi.',
        ]);

        Event::create($data);

        return redirect()->route('event.index')->with('success', 'Event berhasil ditambahkan.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()->route('event.index')->with('success', 'Event dihapus.');
    }

    /** Anggota mendaftar ke event. Kuota kosong / 0 berarti tanpa batas. */
    public function daftar(Event $event): RedirectResponse
    {
        if ($event->tanggal_selesai ? $event->tanggal_selesai->lt(today()) : $event->tanggal_mulai?->lt(today())) {
            return back()->with('error', 'Event ini sudah berlalu.');
        }

        if ($event->peserta()->where('user_id', Auth::id())->exists()) {
            return back()->with('error', 'Kamu sudah terdaftar di event ini.');
        }

        if ($event->kuota > 0 && $event->peserta()->count() >= $event->kuota) {
            return back()->with('error', 'Kuota event sudah penuh.');
        }

        $event->peserta()->create([
            'user_id' => Auth::id(),
            'status_pendaftaran' => EventPeserta::TERDAFTAR,
            'tanggal_daftar' => today(),
        ]);

        return back()->with('success', 'Berhasil mendaftar event!');
    }
}
