<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class LaporanController extends Controller
{
    // Tarif denda per hari keterlambatan (contoh, silakan diubah).
    private const TARIF_DENDA = 1000;

    public function index()
    {
        return view('laporan.index');
    }

    public function peminjaman(Request $request)
    {
        $query = Peminjaman::with(['user', 'detail.buku'])->orderByDesc('tanggal_pinjam');
        if ($request->filled('dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->sampai);
        }
        $data = $query->get();

        return view('laporan.detail', [
            'judul' => 'Laporan Peminjaman',
            'deskripsi' => 'Riwayat transaksi peminjaman buku.',
            'ikon' => 'bi-box-arrow-up-right',
            'filter' => 'tanggal',
            'ringkasan' => [
                ['Total Transaksi', $data->count()],
                ['Sudah Dikembalikan', $data->whereNotNull('tanggal_kembali')->count()],
                ['Belum Dikembalikan', $data->whereNull('tanggal_kembali')->count()],
            ],
            'kolom' => ['Peminjam', 'Buku', 'Tgl Pinjam', 'Jatuh Tempo', 'Tgl Kembali', 'Status'],
            'baris' => $data->map(fn ($p) => [
                $p->user->nama ?? '-',
                $p->detail->map(fn ($d) => $d->buku->judul ?? null)->filter()->join(', ') ?: '-',
                $this->tgl($p->tanggal_pinjam),
                $this->tgl($p->tanggal_jatuh_tempo),
                $this->tgl($p->tanggal_kembali),
                ucfirst((string) $p->status) ?: '-',
            ])->all(),
            'catatan' => null,
        ]);
    }

    public function pengunjung(Request $request)
    {
        $query = Absensi::with('user')->orderByDesc('tanggal');
        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }
        $data = $query->get();

        return view('laporan.detail', [
            'judul' => 'Laporan Pengunjung',
            'deskripsi' => 'Kehadiran anggota di perpustakaan (dari data absensi).',
            'ikon' => 'bi-person-lines-fill',
            'filter' => 'tanggal',
            'ringkasan' => [
                ['Total Kunjungan', $data->count()],
                ['Pengunjung Unik', $data->pluck('user_id')->unique()->count()],
                ['Kunjungan Hari Ini', $data->filter(fn ($a) => $a->tanggal && Carbon::parse($a->tanggal)->isToday())->count()],
            ],
            'kolom' => ['Anggota', 'Peran', 'Tanggal', 'Waktu Masuk', 'Metode'],
            'baris' => $data->map(fn ($a) => [
                $a->user->nama ?? '-',
                ucfirst((string) ($a->user->role ?? '')) ?: '-',
                $this->tgl($a->tanggal),
                $a->waktu_masuk ? substr($a->waktu_masuk, 0, 5) : '-',
                ucfirst((string) $a->metode) ?: '-',
            ])->all(),
            'catatan' => null,
        ]);
    }

    public function koleksi(Request $request)
    {
        $query = Buku::with(['kategori', 'tipeKoleksi'])->orderBy('judul');
        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }
        $data = $query->get();

        // Nama kolom stok bisa "jumlah" atau "stok", tergantung databasenya.
        $stok = fn ($b) => (int) ($b->jumlah ?? $b->stok ?? 0);

        return view('laporan.detail', [
            'judul' => 'Laporan Koleksi Buku',
            'deskripsi' => 'Daftar koleksi buku beserta jumlah eksemplar.',
            'ikon' => 'bi-book',
            'filter' => 'cari',
            'ringkasan' => [
                ['Total Judul', $data->count()],
                ['Total Eksemplar', $data->sum($stok)],
                ['Stok Habis', $data->filter(fn ($b) => $stok($b) === 0)->count()],
            ],
            'kolom' => ['Judul', 'Penulis', 'Kategori', 'Tipe', 'Stok'],
            'baris' => $data->map(fn ($b) => [
                $b->judul,
                $b->penulis ?: '-',
                $b->kategori->nama_kategori ?? '-',
                $b->tipeKoleksi->nama_tipe ?? '-',
                $stok($b),
            ])->all(),
            'catatan' => null,
        ]);
    }

    public function denda()
    {
        $ada = Schema::hasColumn('peminjaman', 'tanggal_jatuh_tempo');
        $terlambat = collect();

        if ($ada) {
            $terlambat = Peminjaman::with(['user', 'detail.buku'])
                ->whereNotNull('tanggal_jatuh_tempo')
                ->get()
                ->map(function ($p) {
                    $akhir = $p->tanggal_kembali ?? today();
                    $hari = max(0, (int) $p->tanggal_jatuh_tempo->diffInDays($akhir, false));

                    return ['p' => $p, 'hari' => $hari, 'denda' => $hari * self::TARIF_DENDA];
                })
                ->filter(fn ($r) => $r['hari'] > 0)
                ->values();
        }

        $rupiah = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.');

        return view('laporan.detail', [
            'judul' => 'Laporan Denda',
            'deskripsi' => 'Denda keterlambatan pengembalian buku.',
            'ikon' => 'bi-coin',
            'filter' => null,
            'ringkasan' => [
                ['Total Denda', $rupiah($terlambat->sum('denda'))],
                ['Peminjaman Terlambat', $terlambat->count()],
                ['Anggota Berdenda', $terlambat->pluck('p.user_id')->unique()->count()],
            ],
            'kolom' => ['Peminjam', 'Buku', 'Jatuh Tempo', 'Tgl Kembali', 'Terlambat', 'Denda', 'Status'],
            'baris' => $terlambat->map(fn ($r) => [
                $r['p']->user->nama ?? '-',
                $r['p']->detail->map(fn ($d) => $d->buku->judul ?? null)->filter()->join(', ') ?: '-',
                $this->tgl($r['p']->tanggal_jatuh_tempo),
                $this->tgl($r['p']->tanggal_kembali),
                $r['hari'] . ' hari',
                $rupiah($r['denda']),
                $r['p']->tanggal_kembali ? 'Sudah kembali' : 'Belum kembali',
            ])->all(),
            'catatan' => $ada
                ? 'Denda dihitung ' . $rupiah(self::TARIF_DENDA) . ' per hari (tarif contoh), dari tanggal jatuh tempo sampai tanggal kembali atau hari ini.'
                : 'Kolom tanggal_jatuh_tempo tidak ditemukan di tabel peminjaman, jadi denda belum bisa dihitung.',
        ]);
    }

    private function tgl($nilai): string
    {
        return $nilai ? Carbon::parse($nilai)->format('d M Y') : '-';
    }
}