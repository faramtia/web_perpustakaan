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
            ->when(
                $request->q,
                fn ($q) => $q
                    ->where('judul', 'like', "%{$request->q}%")
                    ->orWhere('penulis', 'like', "%{$request->q}%")
            )
            ->orderByDesc('tahun_terbit')
            ->paginate(10)
            ->withQueryString();

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
            'tahun_terbit' => ['nullable', 'digits:4'],
            'file_jurnal' => ['nullable', 'string', 'max:255'],
        ]);

        Ejurnal::create($data);

        return redirect()->route('ejurnal.index')->with('success', 'E-jurnal ditambahkan.');
    }
}
