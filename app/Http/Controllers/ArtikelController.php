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
    public function index(): View
    {
        return view('artikel.index', [
            'artikel' => Artikel::with(['penulis', 'kategori'])->orderByDesc('tanggal_upload')->orderByDesc('id')->paginate(9),
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
        ]);

        Artikel::create([
            ...$data,
            'user_id' => Auth::id(),
            'tanggal_upload' => today(),
        ]);

        return redirect()->route('artikel.index')->with('success', 'Artikel dipublikasikan.');
    }
}
