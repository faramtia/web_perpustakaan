<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LokasiController extends Controller
{
    public function index(): View
    {
        return view('master.lokasi', [
            'items' => Lokasi::withCount('buku')->orderBy('nama_ruang')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['nama_ruang' => ['required', 'string', 'max:100']]);

        Lokasi::create($request->only('nama_ruang'));

        return back()->with('success', 'Lokasi ditambahkan.');
    }

    public function destroy(Lokasi $lokasi): RedirectResponse
    {
        $lokasi->delete();

        return back()->with('success', 'Lokasi dihapus.');
    }
}
