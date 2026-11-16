<?php

namespace App\Http\Controllers;

use App\Models\TugasAkhir;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TugasAkhirController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = TugasAkhir::with(['user.jenisUser', 'reviewer']);

        if ($user->isAnggota()) {
            $query->where('user_id', $user->id);
        }

        if (in_array($request->status, [TugasAkhir::MENUNGGU, TugasAkhir::DISETUJUI, TugasAkhir::DITOLAK], true)) {
            $query->where('status', $request->status);
        }

        if ($user->isStaff() && $request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($w) use ($q) {
                $w->where('judul', 'like', "%{$q}%")
                    ->orWhereHas('user', fn ($u) => $u->where('nama', 'like', "%{$q}%"));
            });
        }

        $hitung = $user->isAnggota() ? TugasAkhir::where('user_id', $user->id) : TugasAkhir::query();

        return view('tugas-akhir.index', [
            'tugasAkhir' => $query->latest('id')->paginate(10)->withQueryString(),
            'ringkasan' => [
                TugasAkhir::MENUNGGU => (clone $hitung)->where('status', TugasAkhir::MENUNGGU)->count(),
                TugasAkhir::DISETUJUI => (clone $hitung)->where('status', TugasAkhir::DISETUJUI)->count(),
                TugasAkhir::DITOLAK => (clone $hitung)->where('status', TugasAkhir::DITOLAK)->count(),
            ],
            // Berkas hanya ditautkan kalau benar-benar ada di storage (butuh `php artisan storage:link`).
            'adaBerkas' => fn (?string $path) => $path && Storage::disk('public')->exists($path),
        ]);
    }

    /** Koleksi tugas akhir yang sudah disetujui (publik; PDF hanya untuk yang login). */
    public function publik(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $koleksi = TugasAkhir::with('user')
            ->where('status', TugasAkhir::DISETUJUI)
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('judul', 'like', "%{$q}%")
                    ->orWhere('pembimbing', 'like', "%{$q}%")
                    ->orWhereHas('user', fn ($u) => $u->where('nama', 'like', "%{$q}%"));
            }))
            ->latest('id')
            ->paginate(9)
            ->withQueryString();

        return view('tugas-akhir.publik', [
            'koleksi' => $koleksi,
            'adaBerkas' => fn (?string $path) => $path && Storage::disk('public')->exists($path),
        ]);
    }

    /** Halaman detail tugas akhir. Yang Disetujui terbuka untuk umum; lainnya hanya staf dan pemiliknya. */
    public function show(TugasAkhir $tugasAkhir): View
    {
        abort_unless($this->boleh($tugasAkhir), 404);

        $tugasAkhir->load('user');

        return view('tugas-akhir.show', [
            'ta' => $tugasAkhir,
            'adaBerkas' => $tugasAkhir->file_tugas && Storage::disk('public')->exists($tugasAkhir->file_tugas),
        ]);
    }

    /** Tampilkan PDF di browser (untuk viewer di halaman detail). */
    public function berkas(TugasAkhir $tugasAkhir)
    {
        abort_unless($this->boleh($tugasAkhir) && $this->berkasAda($tugasAkhir), 404);

        return Storage::disk('public')->response($tugasAkhir->file_tugas, $this->namaBerkas($tugasAkhir), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$this->namaBerkas($tugasAkhir).'"',
        ]);
    }

    /** Unduh PDF. */
    public function unduh(TugasAkhir $tugasAkhir)
    {
        abort_unless($this->boleh($tugasAkhir) && $this->berkasAda($tugasAkhir), 404);

        return Storage::disk('public')->download($tugasAkhir->file_tugas, $this->namaBerkas($tugasAkhir));
    }

    private function boleh(TugasAkhir $ta): bool
    {
        if ($ta->status === TugasAkhir::DISETUJUI) {
            return true;
        }
        $user = Auth::user();

        return $user && ($user->isStaff() || $user->id === $ta->user_id);
    }

    private function berkasAda(TugasAkhir $ta): bool
    {
        return $ta->file_tugas && Storage::disk('public')->exists($ta->file_tugas);
    }

    private function namaBerkas(TugasAkhir $ta): string
    {
        return \Illuminate\Support\Str::slug(\Illuminate\Support\Str::limit($ta->judul, 80, ''), '_').'.pdf';
    }

    /**
     * Petugas/admin memasukkan tugas akhir milik seorang anggota (dicari lewat NIM/NIP).
     * Anggota tidak bisa menginput sendiri. Karena petugas yang memasukkan, statusnya langsung Disetujui.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nim_nip' => ['required', 'string', 'max:50'],
            'judul' => ['required', 'string', 'max:250'],
            'pembimbing' => ['nullable', 'string', 'max:150'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ], [
            'nim_nip.required' => 'NIM/NIP penulis wajib diisi.',
            'judul.required' => 'Judul tugas akhir wajib diisi.',
            'file.required' => 'Pilih berkas PDF tugas akhir.',
            'file.uploaded' => 'Berkas gagal diunggah. Ukuran melebihi batas server (cek upload_max_filesize dan post_max_size di php.ini).',
            'file.mimes' => 'Berkas harus berformat PDF.',
            'file.max' => 'Ukuran berkas maksimal 10 MB.',
        ]);

        $penulis = User::where('nim_nip', trim($request->nim_nip))->first();

        if (! $penulis || ! $penulis->isAnggota()) {
            return back()->withInput()->withErrors(['nim_nip' => 'NIM/NIP tidak ditemukan sebagai anggota (mahasiswa/dosen).']);
        }

        $path = $request->file('file')->store('tugas-akhir', 'public');

        TugasAkhir::create([
            'user_id' => $penulis->id,
            'judul' => $request->judul,
            'pembimbing' => $request->pembimbing,
            'file_tugas' => $path,
            'status' => TugasAkhir::DISETUJUI,
            'reviewer_id' => Auth::id(),
        ]);

        return back()->with('success', 'Tugas akhir "'.$request->judul.'" milik '.$penulis->nama.' berhasil dimasukkan.');
    }

    /** Mahasiswa mengunggah tugas akhirnya sendiri; berstatus Menunggu sampai direview petugas. */
    public function storeAnggota(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()->role === 'mahasiswa', 403, 'Hanya mahasiswa yang dapat mengunggah tugas akhir.');

        $request->validate([
            'judul' => ['required', 'string', 'max:250'],
            'pembimbing' => ['nullable', 'string', 'max:150'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ], [
            'judul.required' => 'Judul tugas akhir wajib diisi.',
            'file.required' => 'Pilih berkas PDF tugas akhir.',
            'file.uploaded' => 'Berkas gagal diunggah. Ukuran melebihi batas server (cek upload_max_filesize dan post_max_size di php.ini).',
            'file.mimes' => 'Berkas harus berformat PDF.',
            'file.max' => 'Ukuran berkas maksimal 10 MB.',
        ]);

        TugasAkhir::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'pembimbing' => $request->pembimbing,
            'file_tugas' => $request->file('file')->store('tugas-akhir', 'public'),
            'status' => TugasAkhir::MENUNGGU,
        ]);

        return back()->with('success', 'Tugas akhir terkirim dan menunggu review petugas.');
    }

    /** Petugas/admin mengunggah atau mengganti berkas PDF sebuah tugas akhir. */
    public function ubahBerkas(Request $request, TugasAkhir $tugasAkhir): RedirectResponse
    {
        $request->validate(['file' => ['required', 'file', 'mimes:pdf', 'max:10240']], [
            'file.required' => 'Pilih berkas PDF.',
            'file.uploaded' => 'Berkas gagal diunggah. Ukuran melebihi batas server (cek upload_max_filesize dan post_max_size di php.ini).',
            'file.mimes' => 'Berkas harus berformat PDF.',
            'file.max' => 'Ukuran berkas maksimal 10 MB.',
        ]);

        $lama = $tugasAkhir->file_tugas;
        $tugasAkhir->update(['file_tugas' => $request->file('file')->store('tugas-akhir', 'public')]);

        // Hapus berkas lama hanya jika berada di folder unggahan (bukan berkas contoh bawaan).
        if ($lama && str_starts_with($lama, 'tugas-akhir/') && Storage::disk('public')->exists($lama)) {
            Storage::disk('public')->delete($lama);
        }

        return back()->with('success', 'Berkas PDF "'.$tugasAkhir->judul.'" diperbarui.');
    }

    /** Petugas/admin mereview upload tugas akhir. */
    public function review(Request $request, TugasAkhir $tugasAkhir): RedirectResponse
    {
        $request->validate([
            'keputusan' => ['required', 'in:'.TugasAkhir::DISETUJUI.','.TugasAkhir::DITOLAK],
            'catatan_reviewer' => ['nullable', 'string', 'max:1000'],
        ]);

        $tugasAkhir->update([
            'status' => $request->keputusan,
            'reviewer_id' => Auth::id(),
            'catatan_reviewer' => $request->catatan_reviewer,
        ]);

        return back()->with('success', 'Review tersimpan: '.$request->keputusan.'.');
    }
}
