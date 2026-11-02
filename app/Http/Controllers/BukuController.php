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
    /**
     * Katalog (publik) sekaligus daftar kelola buku (admin & petugas).
     * Tampilan menyesuaikan role pengguna yang sedang login.
     */
    public function index(Request $request): View
    {
        // Pengunjung & anggota (cari buku) hanya melihat buku; petugas/admin melihat semua koleksi untuk dikelola.
        $staff = (bool) $request->user()?->isStaff();

        $buku = Buku::with(['kategori', 'lokasi', 'tipeKoleksi'])
            ->when(! $staff, fn ($query) => $query->hanyaBuku())
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = trim($request->q);

                // Dibungkus satu kelompok supaya filter lain (kategori/tipe) tetap berlaku.
                $query->where(function ($sub) use ($q) {
                    $sub->where('judul', 'like', "%{$q}%")
                        ->orWhere('penulis', 'like', "%{$q}%")
                        ->orWhere('penerbit', 'like', "%{$q}%")
                        ->orWhere('isbn', 'like', "%{$q}%");
                });
            })
            ->when($request->filled('kategori'), fn ($query) => $query->where('kategori_id', $request->kategori))
            ->when($request->filled('tipe'), fn ($query) => $query->where('tipe_koleksi_id', $request->tipe))
            ->when($request->ketersediaan === 'tersedia', fn ($query) => $query->where('stok', '>', 0))
            ->when($request->ketersediaan === 'habis', fn ($query) => $query->where('stok', '<=', 0))
            ->orderBy('judul')
            ->paginate(10)
            ->withQueryString();

        return view('buku.index', [
            'buku' => $buku,
            'kategori' => Kategori::orderBy('nama_kategori')->get(),
            'tipeKoleksi' => TipeKoleksi::when(! $staff, fn ($t) => $t->whereNotIn('nama_tipe', TipeKoleksi::NON_BUKU))->orderBy('nama_tipe')->get(),
        ]);
    }

    public function create(): View
    {
        return view('buku.create', $this->pilihan());
    }

    public function store(Request $request): RedirectResponse
    {
        Buku::create($this->validated($request));

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Buku $buku): View
    {
        return view('buku.edit', ['buku' => $buku] + $this->pilihan());
    }

    public function update(Request $request, Buku $buku): RedirectResponse
    {
        $buku->update($this->validated($request));

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku): RedirectResponse
    {
        // Relasi database memakai ON DELETE CASCADE: menghapus buku akan ikut menghapus
        // riwayat peminjaman & reservasinya. Karena itu buku yang punya riwayat tidak boleh dihapus.
        if ($buku->detailPeminjaman()->exists() || $buku->reservasi()->exists()) {
            return back()->with('error', 'Buku ini punya riwayat peminjaman/reservasi sehingga tidak bisa dihapus. Ubah stoknya menjadi 0 kalau sudah tidak dipakai.');
        }

        $buku->delete();

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil dihapus.');
    }

    private function pilihan(): array
    {
        return [
            'kategori' => Kategori::orderBy('nama_kategori')->get(),
            'lokasi' => Lokasi::orderBy('nama_ruang')->get(),
            'tipeKoleksi' => TipeKoleksi::orderBy('nama_tipe')->get(),
        ];
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
            'tahun_terbit' => ['nullable', 'integer', 'between:1901,2155'],
            'isbn' => ['nullable', 'string', 'max:50'],
            'stok' => ['required', 'integer', 'min:0'],
        ], [
            'lokasi_id.required' => 'Lokasi rak wajib dipilih.',
            'stok.required' => 'Stok wajib diisi.',
        ]);
    }
}
