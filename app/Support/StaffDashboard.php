<?php

namespace App\Support;

use App\Models\Absensi;
use App\Models\Buku;
use App\Models\Denda;
use App\Models\Event;
use App\Models\Feedback;
use App\Models\Peminjaman;
use App\Models\Reservasi;
use App\Models\TugasAkhir;
use Illuminate\Support\Carbon;

/**
 * Data dashboard bersama untuk petugas & admin (kartu statistik,
 * grafik peminjaman, transaksi terbaru, tugas & notifikasi).
 */
class StaffDashboard
{
    public static function data(): array
    {
        $dendaBelumLunas = (int) Denda::where('status_bayar', '!=', Denda::LUNAS)->sum('jumlah_denda');

        $stats = [
            ['label' => 'Total Stok Buku',      'value' => number_format(Buku::sum('stok'), 0, ',', '.'), 'icon' => '📖'],
            ['label' => 'Peminjaman Aktif',     'value' => Peminjaman::where('status', Peminjaman::DIPINJAM)->count(), 'icon' => '🔄'],
            ['label' => 'Pengembalian Hari Ini', 'value' => Peminjaman::whereDate('tanggal_kembali', today())->count(), 'icon' => '↩️'],
            ['label' => 'Reservasi Baru',       'value' => Reservasi::where('status', Reservasi::MENUNGGU)->count(), 'icon' => '⏳'],
            ['label' => 'Denda Belum Lunas',    'value' => 'Rp ' . number_format($dendaBelumLunas, 0, ',', '.'), 'icon' => '💰'],
            ['label' => 'Pengunjung Hari Ini',  'value' => Absensi::whereDate('tanggal', today())->count(), 'icon' => '👥'],
        ];

        return [
            'stats' => $stats,
            'chart' => [
                '7'  => self::grafik(7),
                '30' => self::grafik(30),
            ],
            'transaksi' => Peminjaman::with(['user', 'petugas', 'detail.buku'])
                ->latest('id')->take(5)->get(),
            'tugas' => self::tugas(),
        ];
    }

    /** Jumlah peminjaman per hari untuk N hari terakhir. */
    private static function grafik(int $hari): array
    {
        $mulai = today()->subDays($hari - 1);

        $perHari = Peminjaman::whereDate('tanggal_pinjam', '>=', $mulai)
            ->get(['tanggal_pinjam'])
            ->groupBy(fn ($p) => Carbon::parse($p->tanggal_pinjam)->toDateString())
            ->map->count();

        $labels = [];
        $values = [];
        for ($i = 0; $i < $hari; $i++) {
            $d = $mulai->copy()->addDays($i);
            $labels[] = $d->format('d M');
            $values[] = (int) ($perHari[$d->toDateString()] ?? 0);
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /** Daftar tugas/notifikasi yang perlu ditindaklanjuti petugas. */
    private static function tugas(): array
    {
        $items = [];

        $menunggu = Peminjaman::where('status', Peminjaman::MENUNGGU)->count();
        $items[] = ['jenis' => 'tugas', 'icon' => '✅', 'judul' => 'Verifikasi peminjaman',
            'isi' => "$menunggu pengajuan menunggu persetujuan.", 'aktif' => $menunggu > 0,
            'url' => route('petugas.peminjaman.index')];

        $terlambat = Peminjaman::where('status', Peminjaman::DIPINJAM)
            ->whereDate('tanggal_jatuh_tempo', '<', today())->count();
        $items[] = ['jenis' => 'notifikasi', 'icon' => '⚠️', 'judul' => 'Pengembalian terlambat',
            'isi' => "$terlambat buku melewati batas waktu pengembalian.", 'aktif' => $terlambat > 0,
            'url' => route('petugas.peminjaman.index')];

        $reservasi = Reservasi::where('status', Reservasi::MENUNGGU)->count();
        $items[] = ['jenis' => 'tugas', 'icon' => '⏳', 'judul' => 'Reservasi baru',
            'isi' => "$reservasi reservasi buku perlu diproses.", 'aktif' => $reservasi > 0,
            'url' => route('petugas.reservasi.index')];

        $ta = TugasAkhir::where('status', TugasAkhir::MENUNGGU)->count();
        $items[] = ['jenis' => 'tugas', 'icon' => '🎓', 'judul' => 'Review tugas akhir',
            'isi' => "$ta tugas akhir menunggu review.", 'aktif' => $ta > 0,
            'url' => route('epublication.index', ['jenis' => 'tugas-akhir'])];

        $fb = Feedback::where('status', Feedback::BELUM_DIBALAS)->count();
        $items[] = ['jenis' => 'notifikasi', 'icon' => '💬', 'judul' => 'Feedback baru',
            'isi' => "$fb pertanyaan/feedback belum dibalas.", 'aktif' => $fb > 0,
            'url' => route('petugas.feedback.index')];

        $event = Event::whereDate('tanggal_mulai', '>=', today())->orderBy('tanggal_mulai')->first();
        $items[] = ['jenis' => 'notifikasi', 'icon' => '📅', 'judul' => 'Event mendatang',
            'isi' => $event ? $event->judul . ' — ' . Carbon::parse($event->tanggal_mulai)->format('d M Y') : 'Belum ada event mendatang.',
            'aktif' => (bool) $event, 'url' => route('petugas.event.index')];

        return $items;
    }
}
