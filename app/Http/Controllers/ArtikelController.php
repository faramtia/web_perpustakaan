<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ArtikelController extends Controller
{
    public function index(Request $request): View
    {
        return view('artikel.index', [
            'artikel' => Artikel::with(['penulis', 'kategori'])
                ->when($request->filled('q'), fn ($q) => $q->where(function ($w) use ($request) {
                    $w->where('judul', 'like', "%{$request->q}%")->orWhere('isi_artikel', 'like', "%{$request->q}%");
                }))
                ->when($request->filled('kategori'), fn ($q) => $q->where('kategori_id', $request->kategori))
                ->orderByDesc('tanggal_upload')
                ->orderByDesc('id')
                ->paginate(9)
                ->withQueryString(),
            'kategori' => Kategori::orderBy('nama_kategori')->get(),
        ]);
    }

    public function show(Artikel $artikel): View
    {
        $artikel->load(['penulis', 'kategori']);

        return view('artikel.show', [
            'artikel' => $artikel,
            'lainnya' => Artikel::with('kategori')->where('id', '!=', $artikel->id)
                ->orderByDesc('tanggal_upload')->take(4)->get(),
        ]);
    }

    public function create(): View
    {
        return view('artikel.create', [
            'kategori' => Kategori::orderBy('nama_kategori')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:250'],
            'kategori_id' => ['required', 'exists:kategori,id'],
            'isi_artikel' => ['required', 'string'],
        ], [
            'judul.required' => 'Judul wajib diisi.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'isi_artikel.required' => 'Isi artikel wajib diisi.',
        ]);

        Artikel::create([
            ...$data,
            'user_id' => Auth::id(),
            'tanggal_upload' => today(),
        ]);

        return redirect()->route('artikel.index')->with('success', 'Artikel dipublikasikan.');
    }

    public function destroy(Artikel $artikel): RedirectResponse
    {
        $artikel->delete();

        return redirect()->route('artikel.index')->with('success', 'Artikel dihapus.');
    }
}
