<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DendaController extends Controller
{
    /** Staf melihat semua denda; anggota hanya denda miliknya. */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $base = Denda::query();
        if ($user->isAnggota()) {
            $base->whereHas('peminjaman', fn ($q) => $q->where('user_id', $user->id));
        }

        $totalBelumLunas = (clone $base)->where('status_bayar', Denda::BELUM_LUNAS)->sum('jumlah_denda');
        $totalLunas = (clone $base)->where('status_bayar', Denda::LUNAS)->sum('jumlah_denda');
        $jumlahBelumLunas = (clone $base)->where('status_bayar', Denda::BELUM_LUNAS)->count();

        $denda = (clone $base)
            ->with(['peminjaman.user.jenisUser', 'peminjaman.detail.buku'])
            ->when(in_array($request->status, [Denda::BELUM_LUNAS, Denda::LUNAS], true), fn ($q) => $q->where('status_bayar', $request->status))
            ->when(in_array($request->jenis, [Denda::TERLAMBAT, Denda::RUSAK, Denda::HILANG], true), fn ($q) => $q->where('jenis', $request->jenis))
            ->when($request->filled('q') && $user->isStaff(), function ($q) use ($request) {
                $kata = trim($request->q);
                $q->whereHas('peminjaman', fn ($p) => $p
                    ->whereHas('user', fn ($u) => $u->where('nama', 'like', "%{$kata}%")->orWhere('nim_nip', 'like', "%{$kata}%"))
                    ->orWhereHas('detail.buku', fn ($b) => $b->where('judul', 'like', "%{$kata}%")));
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        // Denda berjalan: buku masih dipinjam tapi sudah lewat jatuh tempo (belum tercatat di tabel denda).
        $berjalan = Peminjaman::with(['user.jenisUser', 'detail.buku'])
            ->where('status', Peminjaman::DIPINJAM)
            ->whereDate('tanggal_jatuh_tempo', '<', today())
            ->when($user->isAnggota(), fn ($q) => $q->where('user_id', $user->id))
            ->orderBy('tanggal_jatuh_tempo')
            ->get()
            ->map(function (Peminjaman $p) {
                $p->hari_telat = (int) $p->tanggal_jatuh_tempo->diffInDays(today(), true);
                $p->tarif = (int) ($p->user->jenisUser->denda_per_hari ?? 0);
                $p->estimasi = $p->hari_telat * $p->tarif;

                return $p;
            });

        return view('denda.index', [
            'berjalan' => $berjalan,
            'totalBerjalan' => (int) $berjalan->sum('estimasi'),
            'denda' => $denda,
            'totalBelumLunas' => (int) $totalBelumLunas,
            'totalLunas' => (int) $totalLunas,
            'jumlahBelumLunas' => $jumlahBelumLunas,
            // Buku-buku yang pernah dipinjam, untuk form "catat denda rusak/hilang" (khusus staf).
            'daftarBuku' => $user->isStaff()
                ? DetailPeminjaman::with(['buku', 'peminjaman.user'])
                    ->whereHas('peminjaman', fn ($q) => $q->whereIn('status', [Peminjaman::DIPINJAM, Peminjaman::DIKEMBALIKAN]))
                    ->latest('id')->take(150)->get()
                : collect(),
            'ketentuan' => config('perpus.denda_kerusakan'),
        ]);
    }

    /**
     * Catat denda buku rusak / hilang. Denda terlambat dicatat otomatis saat pengembalian.
     * Tingkat kerusakan memberi nominal default (config/perpus.php); petugas boleh mengubah nominalnya.
     */
    public function store(Request $request): RedirectResponse
    {
        $ketentuan = config('perpus.denda_kerusakan');

        $data = $request->validate([
            'detail_peminjaman_id' => ['required', 'exists:detail_peminjaman,id'],
            'tingkat' => ['required', 'in:'.implode(',', array_keys($ketentuan))],
            'jumlah_denda' => ['required', 'integer', 'min:1000', 'max:10000000'],
            'keterangan' => ['nullable', 'string', 'max:200'],
        ], [
            'detail_peminjaman_id.required' => 'Pilih buku yang rusak atau hilang.',
            'tingkat.required' => 'Pilih tingkat kerusakan.',
            'jumlah_denda.required' => 'Nominal denda wajib diisi.',
            'jumlah_denda.min' => 'Nominal denda minimal Rp1.000.',
        ]);

        $detail = DetailPeminjaman::with('peminjaman')->findOrFail($data['detail_peminjaman_id']);
        $aturan = $ketentuan[$data['tingkat']];

        $keterangan = $aturan['label'].($data['keterangan'] ? ': '.$data['keterangan'] : '');

        Denda::buat($detail->peminjaman, [
            'detail_peminjaman_id' => $detail->id,
            'jenis' => $aturan['jenis'],
            'hari_terlambat' => 0,
            'tarif_per_hari' => 0,
            'jumlah_denda' => $data['jumlah_denda'],
            'status_bayar' => Denda::BELUM_LUNAS,
            'keterangan' => mb_substr($keterangan, 0, 255),
        ]);

        return back()->with('success', 'Denda '.strtolower($aturan['label']).' berhasil dicatat.');
    }

    public function bayar(Denda $denda): RedirectResponse
    {
        if ($denda->status_bayar === Denda::LUNAS) {
            return back()->with('error', 'Denda ini sudah lunas.');
        }

        $denda->update([
            'status_bayar' => Denda::LUNAS,
            'tanggal_bayar' => today(),
        ]);

        return back()->with('success', 'Pembayaran denda berhasil dicatat.');
    }
}
