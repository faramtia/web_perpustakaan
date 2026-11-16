<?php

namespace App\Http\Controllers;

use App\Models\Ejurnal;
use App\Models\Kategori;
use App\Models\TugasAkhir;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\View\View;

/**
 * E-Publication = gabungan E-Jurnal + Tugas Akhir.
 *
 * - Pengunjung & anggota (mahasiswa/dosen): hanya MELIHAT (tanpa input/upload).
 * - Admin & petugas: menginput e-jurnal dan tugas akhir.
 */
class EpublicationController extends Controller
{
    public const JENIS_JURNAL = 'jurnal';
    public const JENIS_TA = 'tugas-akhir';

    public function index(Request $request): View
    {
        $jenis = $request->query('jenis') === self::JENIS_TA ? self::JENIS_TA : self::JENIS_JURNAL;
        $q = trim((string) $request->query('q', ''));
        $bisaKelola = $this->bisaKelola();

        if ($jenis === self::JENIS_TA) {
            $query = TugasAkhir::with(['user', 'reviewer'])
                // Yang belum disetujui hanya terlihat oleh admin/petugas.
                ->when(! $bisaKelola, fn ($x) => $x->where('status', TugasAkhir::DISETUJUI))
                ->when($q !== '', fn ($x) => $x->where(function ($w) use ($q) {
                    $w->where('judul', 'like', "%{$q}%")
                        ->orWhere('pembimbing', 'like', "%{$q}%")
                        ->orWhereHas('user', fn ($u) => $u->where('nama', 'like', "%{$q}%"));
                }))
                ->latest('id');
        } else {
            $query = Ejurnal::with('kategori')
                ->when($request->query('kategori_id'), fn ($x, $k) => $x->where('kategori_id', $k))
                ->when($q !== '', fn ($x) => $x->where(function ($w) use ($q) {
                    $w->where('judul', 'like', "%{$q}%")
                        ->orWhere('penulis', 'like', "%{$q}%");
                }))
                ->orderByDesc('tahun_terbit')
                ->orderByDesc('id');
        }

        return view('epublication.index', [
            'jenis' => $jenis,
            'items' => $query->paginate(10)->withQueryString(),
            'bisaKelola' => $bisaKelola,
            'kategori' => Kategori::orderBy('nama_kategori')->get(),
            'jumlahJurnal' => Ejurnal::count(),
            'jumlahTa' => TugasAkhir::when(! $bisaKelola, fn ($x) => $x->where('status', TugasAkhir::DISETUJUI))->count(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('epublication.create', [
            'jenis' => $request->query('jenis') === self::JENIS_TA ? self::JENIS_TA : self::JENIS_JURNAL,
            'kategori' => Kategori::orderBy('nama_kategori')->get(),
            'anggota' => User::with('jenisUser')->orderBy('nama')->get()
                ->filter(fn ($u) => in_array($u->role, ['mahasiswa', 'dosen'], true))
                ->values(),
        ]);
    }

    public function storeJurnal(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kategori_id' => ['required', 'exists:kategori,id'],
            'judul' => ['required', 'string', 'max:200'],
            'penulis' => ['nullable', 'string', 'max:150'],
            'abstrak' => ['required', 'string'],
            'tahun_terbit' => ['nullable', 'digits:4'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
        ]);

        // File PDF versi lengkap (opsional) disimpan di storage; hanya bisa dibaca anggota yang login.
        $data['file_jurnal'] = $request->hasFile('file')
            ? $request->file('file')->store('ejurnal', 'public')
            : null;
        unset($data['file']);

        Ejurnal::create($data);

        return redirect()->route('epublication.index', ['jenis' => self::JENIS_JURNAL])
            ->with('success', 'E-jurnal ditambahkan.');
    }

    /**
     * Baca versi lengkap e-jurnal. Hanya untuk yang sudah login (route di dalam middleware auth).
     * File dialirkan lewat aplikasi, jadi tidak ada link publik ke PDF-nya.
     */
    public function bacaJurnal(Ejurnal $ejurnal): BinaryFileResponse|RedirectResponse
    {
        $disk = Storage::disk('public');

        if (! $ejurnal->file_jurnal || ! $disk->exists($ejurnal->file_jurnal)) {
            return back()->with('error', 'File lengkap jurnal ini belum tersedia di sistem. Silakan baca di ruang jurnal perpustakaan.');
        }

        return response()->file($disk->path($ejurnal->file_jurnal), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.\Illuminate\Support\Str::slug($ejurnal->judul).'.pdf"',
        ]);
    }

    public function storeTugasAkhir(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:user,id'],
            'judul' => ['required', 'string', 'max:250'],
            'pembimbing' => ['nullable', 'string', 'max:150'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $path = $request->file('file')->store('tugas-akhir', 'public');

        // Diinput langsung oleh admin/petugas, jadi langsung berstatus Disetujui (terbit).
        TugasAkhir::create([
            'user_id' => $request->user_id,
            'judul' => $request->judul,
            'pembimbing' => $request->pembimbing,
            'file_tugas' => $path,
            'status' => TugasAkhir::DISETUJUI,
            'reviewer_id' => Auth::id(),
        ]);

        return redirect()->route('epublication.index', ['jenis' => self::JENIS_TA])
            ->with('success', 'Tugas akhir ditambahkan ke E-Publication.');
    }

    /**
     * Untuk data lama yang masih berstatus Menunggu: admin/petugas setujui atau tolak.
     */
    public function reviewTugasAkhir(Request $request, TugasAkhir $tugasAkhir): RedirectResponse
    {
        $request->validate([
            'keputusan' => ['required', 'in:'.TugasAkhir::DISETUJUI.','.TugasAkhir::DITOLAK],
            'catatan_reviewer' => ['nullable', 'string'],
        ]);

        $tugasAkhir->update([
            'status' => $request->keputusan,
            'catatan_reviewer' => $request->catatan_reviewer,
            'reviewer_id' => Auth::id(),
        ]);

        return back()->with('success', 'Status tugas akhir diperbarui.');
    }

    private function bisaKelola(): bool
    {
        return Auth::check() && in_array(Auth::user()->role, ['admin', 'petugas'], true);
    }
}
