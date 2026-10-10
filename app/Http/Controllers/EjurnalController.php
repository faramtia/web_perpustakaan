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
<<<<<<< HEAD
            ->when($request->q, fn ($q) => $q->where('judul', 'like', "%{$request->q}%"))
            ->orderBy('id', 'desc')
            ->paginate(10);
=======
            ->when(
                $request->q,
                fn ($q) => $q
                    ->where('judul', 'like', "%{$request->q}%")
                    ->orWhere('penulis', 'like', "%{$request->q}%")
            )
            ->orderByDesc('tahun_terbit')
            ->paginate(10)
            ->withQueryString();
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3

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
<<<<<<< HEAD
            'tahun' => ['nullable', 'digits:4'],
=======
            'tahun_terbit' => ['nullable', 'digits:4'],
            'file_jurnal' => ['nullable', 'string', 'max:255'],
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
        ]);

        // Form mengirim "tahun", database menyimpan di kolom "tahun_terbit"
        $data['tahun_terbit'] = $data['tahun'] ?? null;
        unset($data['tahun']);
        // Catatan: tabel ejurnal tidak punya kolom lokasi_rak, jadi isian itu tidak disimpan

        Ejurnal::create($data);

        return redirect()->route('ejurnal.index')->with('success', 'E-jurnal ditambahkan.');
    }
}