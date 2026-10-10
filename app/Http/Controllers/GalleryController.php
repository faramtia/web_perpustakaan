<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Gallery tanpa tabel database: foto disimpan di storage/app/public/gallery.
 * Syarat: sudah menjalankan  php artisan storage:link  (sekali saja).
 */
class GalleryController extends Controller
{
    private const FOLDER = 'gallery';

    public function index(): View
    {
        $disk = Storage::disk('public');

        $foto = collect($disk->files(self::FOLDER))
            ->filter(fn ($f) => preg_match('/\.(jpe?g|png|gif|webp)$/i', $f))
            ->map(function ($f) use ($disk) {
                $nama = basename($f);
                // nama file disimpan sebagai "kode__judul-foto.ext" -> ambil bagian judul
                $judul = Str::headline(pathinfo(Str::after($nama, '__'), PATHINFO_FILENAME));

                return [
                    'nama'  => $nama,
                    'judul' => $judul,
                    'url'   => asset('storage/' . $f),
                    'waktu' => $disk->lastModified($f),
                ];
            })
            ->sortByDesc('waktu')
            ->values();

        return view('gallery.index', compact('foto'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'foto'   => ['required', 'array', 'max:10'],
            'foto.*' => ['image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
        ], [
            'foto.required' => 'Pilih minimal satu foto.',
            'foto.*.image'  => 'File harus berupa gambar.',
            'foto.*.mimes'  => 'Format yang diizinkan: jpg, jpeg, png, gif, webp.',
            'foto.*.max'    => 'Ukuran tiap foto maksimal 2 MB.',
        ]);

        foreach ($request->file('foto') as $file) {
            $judul = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'foto';
            $nama  = uniqid() . '__' . $judul . '.' . $file->extension();
            $file->storeAs(self::FOLDER, $nama, 'public');
        }

        return back()->with('success', count($request->file('foto')) . ' foto berhasil diunggah.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate(['nama' => ['required', 'string']]);

        // basename() mencegah penghapusan file di luar folder gallery
        Storage::disk('public')->delete(self::FOLDER . '/' . basename($request->nama));

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}