<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\TipeKoleksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BukuController extends Controller
{
    public function index(Request $request): View
    {
        $buku = Buku::with(['kategori', 'lokasi', 'tipeKoleksi'])
<<<<<<< HEAD
            ->when($request->q, function ($query, $q) {          // <-- DIUBAH
                $query->where(function ($sub) use ($q) {
                    $sub->where('judul', 'like', "%{$q}%")
                        ->orWhere('penulis', 'like', "%{$q}%");
                });
            })
            ->orderBy('id', 'desc')
=======
            ->when($request->q, fn ($query) => $query->where('judul', 'like', "%{$request->q}%")
            ->orWhere('penulis', 'like', "%{$request->q}%"))
            ->orderByDesc('tahun_terbit')
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
            ->paginate(10)
            ->withQueryString();

        return view('buku.index', compact('buku'));
    }

    public function create(): View
    {
        return view('buku.create', [
            'kategori' => Kategori::orderBy('nama_kategori')->get(),
            'lokasi' => Lokasi::orderBy('nama_ruang')->get(),
            'tipeKoleksi' => TipeKoleksi::orderBy('nama_tipe')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        Buku::create($data);

<<<<<<< HEAD
        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil ditambahkan.'); // <-- DIUBAH
=======
        return redirect()->route('katalog.index')->with('success', 'Buku berhasil ditambahkan.');
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
    }

    public function edit(Buku $buku): View
    {
        return view('buku.edit', [
            'buku' => $buku,
            'kategori' => Kategori::orderBy('nama_kategori')->get(),
            'lokasi' => Lokasi::orderBy('nama_ruang')->get(),
            'tipeKoleksi' => TipeKoleksi::orderBy('nama_tipe')->get(),
        ]);
    }

    public function update(Request $request, Buku $buku): RedirectResponse
    {
        $data = $this->validated($request);

        $buku->update($data);

<<<<<<< HEAD
        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil diperbarui.'); // <-- DIUBAH
=======
        return redirect()->route('katalog.index')->with('success', 'Buku berhasil diperbarui.');
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
    }

    public function destroy(Buku $buku): RedirectResponse
    {
        $buku->delete();

<<<<<<< HEAD
        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil dihapus.'); // <-- DIUBAH
=======
        return redirect()->route('katalog.index')->with('success', 'Buku berhasil dihapus.');
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'kategori_id' => ['required', 'exists:kategori,id'],
            'lokasi_id' => ['required', 'exists:lokasi,id'],
            'tipe_koleksi_id' => ['required', 'exists:tipe_koleksi,id'],
            'judul' => ['required', 'string', 'max:200'],
            'penulis' => ['nullable', 'string', 'max:150'],
            'penerbit' => ['nullable', 'string', 'max:150'],
            'tahun_terbit' => ['nullable', 'digits:4'],
<<<<<<< HEAD
            'isbn' => ['nullable', 'string', 'max:20'],
=======
            'isbn' => ['nullable', 'string', 'max:50'],
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
            'stok' => ['required', 'integer', 'min:0'],
        ]);
    }
}