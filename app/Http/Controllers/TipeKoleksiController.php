<?php

namespace App\Http\Controllers;

use App\Models\TipeKoleksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TipeKoleksiController extends Controller
{
    public function index(): View
    {
        return view('master.tipe-koleksi', [
            'items' => TipeKoleksi::withCount('buku')->orderBy('nama_tipe')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['nama_tipe' => ['required', 'string', 'max:50']]);

        TipeKoleksi::create($request->only('nama_tipe'));

        return back()->with('success', 'Tipe koleksi ditambahkan.');
    }

    public function destroy(TipeKoleksi $tipeKoleksi): RedirectResponse
    {
        $tipeKoleksi->delete();

        return back()->with('success', 'Tipe koleksi dihapus.');
    }
}
