<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Buku;
use App\Models\Denda;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index', [
            'jumlah' => [
                'peminjaman' => Peminjaman::count(),
                'pengunjung' => Absensi::count(),
                'koleksi' => Buku::count(),
                'denda' => Denda::count(),
            ],
        ]);
    }

    /** Satu pintu untuk keempat laporan; ?export=csv mengunduh versi CSV. */
    public function show(Request $request, string $jenis)
    {
        $laporan = match ($jenis) {
            'peminjaman' => $this->peminjaman($request),
            'pengunjung' => $this->pengunjung($request),
            'koleksi' => $this->koleksi($request),
            'denda' => $this->denda($request),
        };
        $laporan['jenis'] = $jenis;

        if ($request->query('export') === 'csv') {
            return response()->streamDownload(function () use ($laporan) {
                $out = fopen('php://output', 'w');
                fwrite($out, "\xEF\xBB\xBF"); // BOM supaya Excel membaca UTF-8 dengan benar
                fputcsv($out, $laporan['kolom']);
                foreach ($laporan['baris'] as $baris) {
                    fputcsv($out, $baris);
                }
                fclose($out);
            }, 'laporan-'.$jenis.'-'.today()->format('Ymd').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
        }

        return view('laporan.detail', $laporan);
    }

    private function peminjaman(Request $request): array
    {
        $data = Peminjaman::with(['user', 'detail.buku'])
            ->when($request->filled('dari'), fn ($q) => $q->whereDate('tanggal_pinjam', '>=', $request->dari))
            ->when($request->filled('sampai'), fn ($q) => $q->whereDate('tanggal_pinjam', '<=', $request->sampai))
            ->orderByDesc('tanggal_pinjam')
            ->orderByDesc('id')
            ->get();

        return [
            'judul' => 'Laporan Peminjaman',
            'deskripsi' => 'Riwayat transaksi peminjaman buku.',
            'ikon' => 'bi-box-arrow-up-right',
            'filter' => 'tanggal',
            'ringkasan' => [
                ['Total Transaksi', $data->count()],
                ['Sudah Dikembalikan', $data->where('status', Peminjaman::DIKEMBALIKAN)->count()],
                ['Sedang Dipinjam', $data->where('status', Peminjaman::DIPINJAM)->count()],
            ],
            'kolom' => ['Peminjam', 'Buku', 'Tgl Pinjam', 'Jatuh Tempo', 'Tgl Kembali', 'Status'],
            'baris' => $data->map(fn ($p) => [
                $p->user->nama ?? '-',
                $p->judul_buku,
                $this->tgl($p->tanggal_pinjam),
                $this->tgl($p->tanggal_jatuh_tempo),
                $this->tgl($p->tanggal_kembali),
                $p->status_tampil ?: '-',
            ])->all(),
            'catatan' => null,
        ];
    }

    private function pengunjung(Request $request): array
    {
        $data = Absensi::with('user.jenisUser')
            ->when($request->filled('dari'), fn ($q) => $q->whereDate('tanggal', '>=', $request->dari))
            ->when($request->filled('sampai'), fn ($q) => $q->whereDate('tanggal', '<=', $request->sampai))
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get();

        return [
            'judul' => 'Laporan Pengunjung',
            'deskripsi' => 'Kehadiran anggota di perpustakaan (dari data absensi).',
            'ikon' => 'bi-person-lines-fill',
            'filter' => 'tanggal',
            'ringkasan' => [
                ['Total Kunjungan', $data->count()],
                ['Pengunjung Unik', $data->pluck('user_id')->unique()->count()],
                ['Kunjungan Hari Ini', $data->filter(fn ($a) => $a->tanggal && $a->tanggal->isToday())->count()],
            ],
            'kolom' => ['Anggota', 'Peran', 'Tanggal', 'Waktu Masuk'],
            'baris' => $data->map(fn ($a) => [
                $a->user->nama ?? '-',
                ucfirst((string) ($a->user->role ?? '')) ?: '-',
                $this->tgl($a->tanggal),
                $a->waktu_masuk ? substr($a->waktu_masuk, 0, 5) : '-',
            ])->all(),
            'catatan' => null,
        ];
    }

    private function koleksi(Request $request): array
    {
        $data = Buku::with(['kategori', 'tipeKoleksi', 'lokasi'])
            ->when($request->filled('q'), fn ($q) => $q->where('judul', 'like', '%'.$request->q.'%'))
            ->orderBy('judul')
            ->get();

        return [
            'judul' => 'Laporan Koleksi Buku',
            'deskripsi' => 'Daftar koleksi buku beserta jumlah eksemplar.',
            'ikon' => 'bi-book',
            'filter' => 'cari',
            'ringkasan' => [
                ['Total Judul', $data->count()],
                ['Total Eksemplar', $data->sum('stok')],
                ['Stok Habis', $data->filter(fn ($b) => (int) $b->stok === 0)->count()],
            ],
            'kolom' => ['Judul', 'Penulis', 'Kategori', 'Tipe', 'Lokasi', 'Stok'],
            'baris' => $data->map(fn ($b) => [
                $b->judul,
                $b->penulis ?: '-',
                $b->kategori->nama_kategori ?? '-',
                $b->tipeKoleksi->nama_tipe ?? '-',
                $b->lokasi->nama_ruang ?? '-',
                (int) $b->stok,
            ])->all(),
            'catatan' => null,
        ];
    }

    /** Denda diambil dari tabel `denda` (terlambat, rusak, hilang), bukan dihitung ulang. */
    private function denda(Request $request): array
    {
        $data = Denda::with(['peminjaman.user', 'peminjaman.detail.buku'])
            ->when(in_array($request->status, [Denda::BELUM_LUNAS, Denda::LUNAS], true), fn ($q) => $q->where('status_bayar', $request->status))
            ->orderByDesc('id')
            ->get();

        $rupiah = fn ($n) => 'Rp '.number_format((int) $n, 0, ',', '.');

        return [
            'judul' => 'Laporan Denda',
            'deskripsi' => 'Denda keterlambatan, buku rusak, dan buku hilang.',
            'ikon' => 'bi-coin',
            'filter' => 'status-denda',
            'ringkasan' => [
                ['Total Denda', $rupiah($data->sum('jumlah_denda'))],
                ['Belum Lunas', $rupiah($data->where('status_bayar', Denda::BELUM_LUNAS)->sum('jumlah_denda'))],
                ['Sudah Lunas', $rupiah($data->where('status_bayar', Denda::LUNAS)->sum('jumlah_denda'))],
            ],
            'kolom' => ['Peminjam', 'Buku', 'Jenis', 'Keterangan', 'Denda', 'Status', 'Tgl Bayar'],
            'baris' => $data->map(fn ($d) => [
                $d->peminjaman->user->nama ?? '-',
                $d->peminjaman?->judul_buku ?? '-',
                ucfirst($d->jenis),
                $d->keterangan ?: '-',
                $rupiah($d->jumlah_denda),
                $d->status_bayar,
                $this->tgl($d->tanggal_bayar),
            ])->all(),
            'catatan' => 'Denda keterlambatan dihitung otomatis saat buku dikembalikan: hari terlambat × tarif per hari sesuai role peminjam (diatur di Pengaturan).',
        ];
    }

    private function tgl($nilai): string
    {
        return $nilai ? Carbon::parse($nilai)->format('d M Y') : '-';
    }
}
