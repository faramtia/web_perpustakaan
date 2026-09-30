<?php

namespace App\Http\Controllers;

use App\Models\Ejurnal;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EjurnalController extends Controller
{
    public function index(Request $request): View
    {
        $ejurnal = Ejurnal::with('kategori')
            ->when($request->q, fn ($q) => $q->where('judul', 'like', "%{$request->q}%"))
            ->latest()
            ->paginate(10);

        return view('ejurnal.index', compact('ejurnal'));
    }

    public function create(): View
    {
        return view('ejurnal.create', ['kategori' => Kategori::orderBy('nama_kategori')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kategori_id' => ['nullable', 'exists:kategori,id'],
            'judul' => ['required', 'string', 'max:255'],
            'penulis' => ['nullable', 'string', 'max:150'],
            'abstrak' => ['required', 'string'],
            'tahun' => ['nullable', 'digits:4'],
            'lokasi_rak' => ['nullable', 'string', 'max:100'],
        ]);

        Ejurnal::create($data);

        return redirect()->route('ejurnal.index')->with('success', 'E-jurnal ditambahkan.');
    }
}
