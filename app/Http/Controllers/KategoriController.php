<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KategoriController extends Controller
{
    public function index(): View
    {
        return view('master.kategori', [
            'items' => Kategori::withCount('buku')->orderBy('nama_kategori')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['nama_kategori' => ['required', 'string', 'max:150']]);

        Kategori::create($request->only('nama_kategori'));

        return back()->with('success', 'Kategori ditambahkan.');
    }

    public function destroy(Kategori $kategori): RedirectResponse
    {
        $kategori->delete();

        return back()->with('success', 'Kategori dihapus.');
    }
}
