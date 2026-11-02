<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Dasar untuk master data sederhana (Kategori, Lokasi Rak, Tipe Koleksi):
 * satu kolom nama, daftar + tambah + ubah + hapus.
 */
abstract class MasterController extends Controller
{
    /**
     * model   : kelas Eloquent
     * kolom   : nama kolom yang diisi pengguna
     * judul   : judul halaman
     * ikon    : kelas bootstrap-icons
     * route   : awalan nama route, mis. 'admin.kategori'
     * deskripsi, label, relasi (relasi yang menahan penghapusan => label)
     */
    abstract protected function config(): array;

    public function index(): View
    {
        $c = $this->config();
        $items = $c['model']::query()->orderBy($c['kolom']);

        foreach (array_keys($c['relasi']) as $relasi) {
            $items->withCount($relasi);
        }

        return view('master.simple', ['c' => $c, 'items' => $items->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $c = $this->config();
        $data = $request->validate([$c['kolom'] => ['required', 'string', 'max:100']]);

        $c['model']::create($data);

        return back()->with('success', $c['label'].' ditambahkan.');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $c = $this->config();
        $data = $request->validate([$c['kolom'] => ['required', 'string', 'max:100']]);

        $c['model']::findOrFail($id)->update($data);

        return back()->with('success', $c['label'].' diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $c = $this->config();
        $item = $c['model']::findOrFail($id);

        // ON DELETE CASCADE di database akan ikut menghapus data yang memakainya,
        // jadi penghapusan hanya boleh kalau belum dipakai.
        foreach ($c['relasi'] as $relasi => $namaData) {
            if ($item->{$relasi}()->exists()) {
                return back()->with('error', "{$c['label']} ini masih dipakai oleh data {$namaData}, jadi tidak bisa dihapus.");
            }
        }

        $item->delete();

        return back()->with('success', $c['label'].' dihapus.');
    }
}
