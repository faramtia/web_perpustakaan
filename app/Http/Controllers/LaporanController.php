<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Buku;
use App\Models\Denda;
use App\Models\Kategori;
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
                'kerusakan' => Denda::whereIn('jenis', [Denda::RUSAK, Denda::HILANG])->count(),
            ],
        ]);
    }

    /** Satu pintu untuk semua laporan; ?export=csv mengunduh versi CSV. */
    public function show(Request $request, string $jenis)
    {
        $laporan = match ($jenis) {
            'peminjaman' => $this->peminjaman($request),
            'pengunjung' => $this->pengunjung($request),
            'koleksi' => $this->koleksi($request),
            'denda' => $this->denda($request),
            'kerusakan' => $this->kerusakan($request),
        };
        $laporan['jenis'] = $jenis;
        $laporan['periode'] = $this->periode($request, $laporan['filter']);
        $laporan['penyaring'] = $this->penyaring($request, $laporan['filter']);

        if ($request->query('export') === 'csv') {
            return response()->streamDownload(function () use ($laporan) {
                $out = fopen('php://output', 'w');
                fwrite($out, "\xEF\xBB\xBF"); // BOM supaya Excel membaca UTF-8 dengan benar
                fputcsv($out, [$laporan['judul']]);
                fputcsv($out, ['Perpustakaan Politeknik Negeri Lhokseumawe']);
                fputcsv($out, ['Periode', $laporan['periode']]);
                if ($laporan['penyaring']) {
                    fputcsv($out, ['Penyaring', implode('; ', $laporan['penyaring'])]);
                }
                fputcsv($out, []);
                fputcsv($out, array_merge(['No'], $laporan['kolom']));
                foreach ($laporan['baris'] as $i => $baris) {
                    fputcsv($out, array_merge([$i + 1], $baris));
                }
                fclose($out);
            }, 'laporan-'.$jenis.'-'.today()->format('Ymd').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
        }

        return view('laporan.detail', $laporan);
    }

    /* ------------------------------------------------------------------ */

    private function peminjaman(Request $request): array
    {
        $status = $request->query('status');

        $data = Peminjaman::with(['user', 'detail.buku'])
            ->when($request->filled('dari'), fn ($q) => $q->whereDate('tanggal_pinjam', '>=', $request->dari))
            ->when($request->filled('sampai'), fn ($q) => $q->whereDate('tanggal_pinjam', '<=', $request->sampai))
            ->when($request->filled('q'), function ($q) use ($request) {
                $kata = trim($request->q);
                $q->where(fn ($w) => $w
                    ->whereHas('user', fn ($u) => $u->where('nama', 'like', "%{$kata}%")->orWhere('nim_nip', 'like', "%{$kata}%"))
                    ->orWhereHas('detail.buku', fn ($b) => $b->where('judul', 'like', "%{$kata}%")));
            })
            ->when($status === 'Terlambat', fn ($q) => $q->where('status', Peminjaman::DIPINJAM)->whereDate('tanggal_jatuh_tempo', '<', today()))
            ->when(in_array($status, [Peminjaman::MENUNGGU, Peminjaman::DIPINJAM, Peminjaman::DIKEMBALIKAN, Peminjaman::DITOLAK], true), fn ($q) => $q->where('status', $status))
            ->orderByDesc('tanggal_pinjam')
            ->orderByDesc('id')
            ->get();

        return [
            'judul' => 'Laporan Peminjaman',
            'deskripsi' => 'Riwayat transaksi peminjaman buku.',
            'ikon' => 'bi-box-arrow-up-right',
            'filter' => [
                'q' => 'Cari peminjam, NIM/NIP, atau judul buku...',
                'tanggal' => 'Tanggal pinjam',
                'status' => ['Menunggu' => 'Menunggu', 'Dipinjam' => 'Dipinjam', 'Terlambat' => 'Terlambat', 'Dikembalikan' => 'Dikembalikan', 'Ditolak' => 'Ditolak'],
            ],
            'ringkasan' => [
                ['Total Transaksi', $data->count()],
                ['Sudah Dikembalikan', $data->where('status', Peminjaman::DIKEMBALIKAN)->count()],
                ['Sedang Dipinjam', $data->where('status', Peminjaman::DIPINJAM)->count()],
            ],
            'kolom' => ['Peminjam', 'NIM/NIP', 'Buku', 'Tgl Pinjam', 'Jatuh Tempo', 'Tgl Kembali', 'Status'],
            'baris' => $data->map(fn ($p) => [
                $p->user->nama ?? '-',
                $p->user->nim_nip ?? '-',
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
            ->when($request->filled('q'), fn ($q) => $q->whereHas('user', fn ($u) => $u
                ->where('nama', 'like', '%'.trim($request->q).'%')
                ->orWhere('nim_nip', 'like', '%'.trim($request->q).'%')))
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get();

        return [
            'judul' => 'Laporan Pengunjung',
            'deskripsi' => 'Kehadiran anggota di perpustakaan (dari data absensi).',
            'ikon' => 'bi-person-lines-fill',
            'filter' => [
                'q' => 'Cari nama atau NIM/NIP...',
                'tanggal' => 'Tanggal kunjungan',
            ],
            'ringkasan' => [
                ['Total Kunjungan', $data->count()],
                ['Pengunjung Unik', $data->pluck('user_id')->unique()->count()],
                ['Kunjungan Hari Ini', $data->filter(fn ($a) => $a->tanggal && $a->tanggal->isToday())->count()],
            ],
            'kolom' => ['Anggota', 'NIM/NIP', 'Peran', 'Tanggal', 'Waktu Masuk'],
            'baris' => $data->map(fn ($a) => [
                $a->user->nama ?? '-',
                $a->user->nim_nip ?? '-',
                ucfirst((string) ($a->user->role ?? '')) ?: '-',
                $this->tgl($a->tanggal),
                $a->waktu_masuk ? substr($a->waktu_masuk, 0, 5) : '-',
            ])->all(),
            'catatan' => null,
        ];
    }

    private function koleksi(Request $request): array
    {
        $kategoriDipilih = $request->query('kategori');

        $data = Buku::with(['kategori', 'tipeKoleksi', 'lokasi'])
            ->when($request->filled('q'), function ($q) use ($request) {
                $kata = trim($request->q);
                $q->where(fn ($w) => $w->where('judul', 'like', "%{$kata}%")
                    ->orWhere('penulis', 'like', "%{$kata}%")
                    ->orWhere('penerbit', 'like', "%{$kata}%")
                    ->orWhere('isbn', 'like', "%{$kata}%"));
            })
            ->when($kategoriDipilih, fn ($q) => $q->where('kategori_id', $kategoriDipilih))
            ->when($request->query('stok') === 'habis', fn ($q) => $q->where('stok', '<=', 0))
            ->when($request->query('stok') === 'tersedia', fn ($q) => $q->where('stok', '>', 0))
            ->orderBy('judul')
            ->get();

        return [
            'judul' => 'Laporan Koleksi Buku',
            'deskripsi' => 'Daftar koleksi buku beserta jumlah eksemplar.',
            'ikon' => 'bi-book',
            'filter' => [
                'q' => 'Cari judul, penulis, penerbit, atau ISBN...',
                'status' => null,
                'select' => [
                    'kategori' => ['Semua kategori', Kategori::orderBy('nama_kategori')->pluck('nama_kategori', 'id')->all()],
                    'stok' => ['Semua stok', ['tersedia' => 'Tersedia', 'habis' => 'Habis']],
                ],
            ],
            'ringkasan' => [
                ['Total Judul', $data->count()],
                ['Total Eksemplar', $data->sum('stok')],
                ['Stok Habis', $data->filter(fn ($b) => (int) $b->stok === 0)->count()],
            ],
            'kolom' => ['Judul', 'Penulis', 'Penerbit', 'Tahun', 'Kategori', 'Tipe', 'Lokasi', 'Stok'],
            'baris' => $data->map(fn ($b) => [
                $b->judul,
                $b->penulis ?: '-',
                $b->penerbit ?: '-',
                $b->tahun_terbit ?: '-',
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
        $data = $this->kueriDenda($request)->get();
        $rupiah = fn ($n) => 'Rp '.number_format((int) $n, 0, ',', '.');

        return [
            'judul' => 'Laporan Denda',
            'deskripsi' => 'Denda keterlambatan, buku rusak, dan buku hilang.',
            'ikon' => 'bi-coin',
            'filter' => [
                'q' => 'Cari peminjam, NIM/NIP, atau judul buku...',
                'tanggal' => 'Tanggal denda dicatat',
                'status' => [Denda::BELUM_LUNAS => 'Belum Lunas', Denda::LUNAS => 'Lunas'],
                'select' => ['jenis' => ['Semua jenis', ['terlambat' => 'Terlambat', 'rusak' => 'Rusak', 'hilang' => 'Hilang']]],
            ],
            'ringkasan' => [
                ['Total Denda', $rupiah($data->sum('jumlah_denda'))],
                ['Belum Lunas', $rupiah($data->where('status_bayar', Denda::BELUM_LUNAS)->sum('jumlah_denda'))],
                ['Sudah Lunas', $rupiah($data->where('status_bayar', Denda::LUNAS)->sum('jumlah_denda'))],
            ],
            'kolom' => ['Tgl Denda', 'Peminjam', 'Buku', 'Jenis', 'Hari Terlambat', 'Denda', 'Status', 'Tgl Bayar'],
            'baris' => $data->map(fn ($d) => [
                $this->tgl($d->created_at),
                ($d->peminjaman->user->nama ?? '-').' ('.($d->peminjaman->user->nim_nip ?? '-').')',
                $d->detail?->buku?->judul ?? $d->peminjaman?->judul_buku ?? '-',
                ucfirst($d->jenis),
                $d->hari_terlambat > 0 ? $d->hari_terlambat.' hari × '.$rupiah($d->tarif_per_hari) : '-',
                $rupiah($d->jumlah_denda),
                $d->status_bayar,
                $this->tgl($d->tanggal_bayar),
            ])->all(),
            'catatan' => 'Denda keterlambatan dihitung otomatis saat buku dikembalikan: hari terlambat × tarif per hari sesuai role peminjam. Denda rusak/hilang mengikuti ketentuan pada halaman Denda.',
        ];
    }

    /** Laporan kerusakan buku: denda berjenis rusak atau hilang beserta buku dan tingkat kerusakannya. */
    private function kerusakan(Request $request): array
    {
        $data = $this->kueriDenda($request)
            ->whereIn('jenis', [Denda::RUSAK, Denda::HILANG])
            ->get();
        $rupiah = fn ($n) => 'Rp '.number_format((int) $n, 0, ',', '.');

        return [
            'judul' => 'Laporan Kerusakan Buku',
            'deskripsi' => 'Buku yang dikembalikan dalam keadaan rusak atau hilang, beserta denda penggantinya.',
            'ikon' => 'bi-journal-x',
            'filter' => [
                'q' => 'Cari peminjam, NIM/NIP, atau judul buku...',
                'tanggal' => 'Tanggal dicatat',
                'status' => [Denda::BELUM_LUNAS => 'Belum Lunas', Denda::LUNAS => 'Lunas'],
                'select' => ['jenis' => ['Rusak & hilang', ['rusak' => 'Rusak', 'hilang' => 'Hilang']]],
            ],
            'ringkasan' => [
                ['Buku Rusak', $data->where('jenis', Denda::RUSAK)->count()],
                ['Buku Hilang', $data->where('jenis', Denda::HILANG)->count()],
                ['Total Denda', $rupiah($data->sum('jumlah_denda'))],
            ],
            'kolom' => ['Tgl Dicatat', 'Judul Buku', 'Peminjam', 'NIM/NIP', 'Kondisi', 'Keterangan', 'Denda', 'Status'],
            'baris' => $data->map(fn ($d) => [
                $this->tgl($d->created_at),
                $d->detail?->buku?->judul ?? $d->peminjaman?->judul_buku ?? '-',
                $d->peminjaman->user->nama ?? '-',
                $d->peminjaman->user->nim_nip ?? '-',
                $d->jenis === Denda::HILANG ? 'Hilang' : (preg_match('/^Rusak (ringan|sedang|berat)/i', (string) $d->keterangan, $m) ? 'Rusak '.strtolower($m[1]) : 'Rusak'),
                preg_replace('/^(Rusak (ringan|sedang|berat)|Hilang):?\s*/i', '', (string) $d->keterangan) ?: '-',
                $rupiah($d->jumlah_denda),
                $d->status_bayar,
            ])->all(),
            'catatan' => 'Tingkat kerusakan dan nominal denda mengikuti ketentuan yang tercantum pada halaman Denda.',
        ];
    }

    /** Kueri denda bersama untuk laporan Denda dan Kerusakan (pencarian, rentang tanggal, status, jenis). */
    private function kueriDenda(Request $request)
    {
        return Denda::with(['peminjaman.user', 'peminjaman.detail.buku', 'detail.buku'])
            ->when(in_array($request->query('status'), [Denda::BELUM_LUNAS, Denda::LUNAS], true), fn ($q) => $q->where('status_bayar', $request->query('status')))
            ->when(in_array($request->query('jenis'), [Denda::TERLAMBAT, Denda::RUSAK, Denda::HILANG], true), fn ($q) => $q->where('jenis', $request->query('jenis')))
            ->when($request->filled('dari'), fn ($q) => $q->whereDate('created_at', '>=', $request->dari))
            ->when($request->filled('sampai'), fn ($q) => $q->whereDate('created_at', '<=', $request->sampai))
            ->when($request->filled('q'), function ($q) use ($request) {
                $kata = trim($request->q);
                $q->where(fn ($w) => $w
                    ->whereHas('peminjaman.user', fn ($u) => $u->where('nama', 'like', "%{$kata}%")->orWhere('nim_nip', 'like', "%{$kata}%"))
                    ->orWhereHas('detail.buku', fn ($b) => $b->where('judul', 'like', "%{$kata}%"))
                    ->orWhereHas('peminjaman.detail.buku', fn ($b) => $b->where('judul', 'like', "%{$kata}%")));
            })
            ->orderByDesc('id');
    }

    /* ------------------------------------------------------------------ */

    private function periode(Request $request, array $filter): string
    {
        if (empty($filter['tanggal'])) {
            return 'Semua data (per '.now()->translatedFormat('d F Y').')';
        }
        $dari = $request->filled('dari') ? Carbon::parse($request->dari)->translatedFormat('d F Y') : null;
        $sampai = $request->filled('sampai') ? Carbon::parse($request->sampai)->translatedFormat('d F Y') : null;

        return match (true) {
            $dari && $sampai => "{$dari} s.d. {$sampai}",
            (bool) $dari => "Sejak {$dari}",
            (bool) $sampai => "Sampai {$sampai}",
            default => 'Semua tanggal',
        };
    }

    /** Daftar penyaring yang sedang aktif, untuk dicetak di bawah kop laporan. */
    private function penyaring(Request $request, array $filter): array
    {
        $aktif = [];
        if ($request->filled('q')) {
            $aktif[] = 'Kata kunci: "'.trim($request->q).'"';
        }
        if (! empty($filter['status']) && $request->filled('status') && isset($filter['status'][$request->status])) {
            $aktif[] = 'Status: '.$filter['status'][$request->status];
        }
        foreach ($filter['select'] ?? [] as $nama => [$semua, $opsi]) {
            if ($request->filled($nama) && isset($opsi[$request->query($nama)])) {
                $aktif[] = ucfirst($nama).': '.$opsi[$request->query($nama)];
            }
        }

        return $aktif;
    }

    private function tgl($nilai): string
    {
        return $nilai ? Carbon::parse($nilai)->format('d M Y') : '-';
    }
}
