<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ArtikelController extends Controller
{
    public function index(): View
    {
        return view('artikel.index', [
            'artikel' => Artikel::with('penulis')->orderBy('id', 'desc')->paginate(9),
        ]);
    }

    public function create(): View
    {
        return view('artikel.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'in:book_review,ta_exposure,artikel'],
            'konten' => ['required', 'string'],
        ]);

        Artikel::create([
            ...$data,
            'penulis_id' => Auth::id(),
            'tanggal_terbit' => today(),
        ]);

        return redirect()->route('artikel.index')->with('success', 'Artikel dipublikasikan.');
    }
}
