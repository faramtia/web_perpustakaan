<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/**
 * Data contoh: 60 buku, 55 pengguna, 55 tugas akhir, 60 transaksi peminjaman (+ denda).
 * Aman dijalankan berulang (cek duplikat lewat ISBN / NIM-NIP / judul).
 * Jalankan:  php artisan db:seed --class=DataContohSeeder
 * Semua akun contoh memakai password: password123
 */
class DataContohSeeder extends Seeder
{
    public function run(): void
    {
        mt_srand(2026);
        $hariIni = Carbon::create(2026, 11, 16);

        $kat = fn (string $n) => $this->ambil('kategori', 'nama_kategori', $n);
        $lok = fn (string $n, string $k) => $this->ambil('lokasi', 'nama_ruang', $n, ['keterangan' => $k]);
        $tipe = fn (string $n) => $this->ambil('tipe_koleksi', 'nama_tipe', $n);

        $tBuku = $tipe('Buku Teks');
        $tRef = $tipe('Buku Referensi');
        $lUmum = $lok('Ruang Koleksi Buku Umum', 'Tempat penyimpanan buku umum perpustakaan');
        $lRef = $lok('Ruang Referensi', 'Tempat penyimpanan buku referensi dan jurnal');
        $lTI = $lok('Rak A1 Lantai 1', 'Koleksi bidang teknologi informasi dan komputer');
        $lTek = $lok('Rak C1 Lantai 2', 'Koleksi teknik mesin, elektro, dan sipil');
        $lMan = $lok('Rak B1 Lantai 1', 'Koleksi manajemen dan akuntansi');

        // ---------- BUKU: [judul, penulis, penerbit, tahun, kategori, lokasi, stok] ----------
        $buku = [
            ['Pemrograman Web dengan PHP dan MySQL', 'Betha Sidik', 'Informatika', 2019, 'Teknologi', $lTI, 6],
            ['Belajar Framework Laravel untuk Pemula', 'Rohi Abdulloh', 'Elex Media Komputindo', 2021, 'Teknologi', $lTI, 5],
            ['Basis Data: Konsep dan Perancangan', 'Fathansyah', 'Informatika', 2018, 'Teknologi', $lTI, 6],
            ['Rekayasa Perangkat Lunak', 'Rosa A.S. dan M. Shalahuddin', 'Informatika', 2018, 'Teknologi', $lTI, 5],
            ['Analisis dan Perancangan Sistem Informasi', 'Jogiyanto H.M.', 'Andi', 2017, 'Teknologi', $lTI, 4],
            ['Algoritma dan Struktur Data dengan C++', 'Rinaldi Munir', 'Informatika', 2016, 'Teknologi', $lTI, 5],
            ['Dasar Pemrograman Python', 'Abdul Kadir', 'Andi', 2020, 'Teknologi', $lTI, 6],
            ['Jaringan Komputer dan Internet', 'Onno W. Purbo', 'Elex Media Komputindo', 2015, 'Teknologi', $lTI, 4],
            ['Sistem Operasi', 'Abdul Kadir', 'Andi', 2014, 'Teknologi', $lTI, 3],
            ['Pengolahan Citra Digital', 'Rinaldi Munir', 'Informatika', 2019, 'Teknologi', $lTI, 4],
            ['Kecerdasan Buatan: Pengantar dan Penerapan', 'Sri Kusumadewi', 'Graha Ilmu', 2016, 'Teknologi', $lTI, 3],
            ['Keamanan Jaringan dan Etika Hacking', 'Budi Raharjo', 'Informatika', 2020, 'Teknologi', $lTI, 4],
            ['Pemrograman Berorientasi Objek dengan Java', 'Adi Nugroho', 'Andi', 2018, 'Teknologi', $lTI, 5],
            ['Pengantar Teknologi Informasi', 'Jogiyanto H.M.', 'Andi', 2013, 'Teknologi', $lTI, 5],
            ['UI/UX Design untuk Pengembang Web', 'Wahana Komputer', 'Andi', 2022, 'Teknologi', $lTI, 3],
            ['Mikrokontroler Arduino untuk Pemula', 'Heri Andrianto', 'Informatika', 2017, 'Teknologi', $lTek, 5],
            ['Dasar Teknik Elektro', 'Zuhal', 'Gramedia Pustaka Utama', 2010, 'Teknik', $lTek, 4],
            ['Rangkaian Listrik Jilid 1', 'William H. Hayt', 'Erlangga', 2011, 'Teknik', $lTek, 4],
            ['Elektronika Digital', 'Malvino dan Leach', 'Erlangga', 2012, 'Teknik', $lTek, 3],
            ['Sistem Tenaga Listrik', 'Abdul Kadir', 'UI Press', 2014, 'Teknik', $lTek, 3],
            ['Mekanika Teknik: Statika', 'Ferdinand L. Singer', 'Erlangga', 2010, 'Teknik', $lTek, 4],
            ['Termodinamika Teknik', 'Yunus A. Cengel', 'Salemba Teknika', 2013, 'Teknik', $lTek, 3],
            ['Mekanika Fluida', 'Frank M. White', 'Erlangga', 2012, 'Teknik', $lTek, 3],
            ['Elemen Mesin', 'Sularso dan Kiyokatsu Suga', 'Pradnya Paramita', 2008, 'Teknik', $lTek, 5],
            ['Proses Manufaktur', 'Mikell P. Groover', 'Andi', 2014, 'Teknik', $lTek, 3],
            ['Gambar Teknik Mesin', 'G. Takeshi Sato', 'Pradnya Paramita', 2009, 'Teknik', $lTek, 4],
            ['Teknik Pengelasan', 'Harsono Wiryosumarto', 'Pradnya Paramita', 2008, 'Teknik', $lTek, 3],
            ['Mesin Konversi Energi', 'Wiranto Arismunandar', 'ITB Press', 2009, 'Teknik', $lTek, 3],
            ['Mekanika Tanah', 'Hary Christady Hardiyatmo', 'Gadjah Mada University Press', 2012, 'Teknik', $lTek, 4],
            ['Struktur Beton Bertulang', 'Istimawan Dipohusodo', 'Gramedia Pustaka Utama', 2010, 'Teknik', $lTek, 4],
            ['Ilmu Ukur Tanah', 'Wongsotjitro', 'Kanisius', 2011, 'Teknik', $lTek, 3],
            ['Teknik Pondasi', 'Hary Christady Hardiyatmo', 'Gadjah Mada University Press', 2011, 'Teknik', $lTek, 3],
            ['Hidrologi Terapan', 'Bambang Triatmodjo', 'Beta Offset', 2010, 'Teknik', $lTek, 3],
            ['Manajemen Konstruksi', 'Iman Soeharto', 'Erlangga', 2009, 'Teknik', $lTek, 3],
            ['Akuntansi Biaya', 'Mulyadi', 'Salemba Empat', 2014, 'Akuntansi & Manajemen', $lMan, 5],
            ['Pengantar Akuntansi 1', 'Carl S. Warren', 'Salemba Empat', 2015, 'Akuntansi & Manajemen', $lMan, 5],
            ['Akuntansi Keuangan Menengah', 'Kieso, Weygandt, Warfield', 'Salemba Empat', 2014, 'Akuntansi & Manajemen', $lMan, 4],
            ['Manajemen Keuangan', 'Eugene F. Brigham', 'Salemba Empat', 2013, 'Akuntansi & Manajemen', $lMan, 4],
            ['Pengantar Manajemen', 'Stephen P. Robbins', 'Erlangga', 2012, 'Akuntansi & Manajemen', $lMan, 5],
            ['Perpajakan Indonesia', 'Mardiasmo', 'Andi', 2018, 'Akuntansi & Manajemen', $lMan, 4],
            ['Sistem Informasi Akuntansi', 'Marshall B. Romney', 'Salemba Empat', 2015, 'Akuntansi & Manajemen', $lMan, 3],
            ['Auditing: Pendekatan Terpadu', 'Alvin A. Arens', 'Erlangga', 2011, 'Akuntansi & Manajemen', $lMan, 3],
            ['Manajemen Pemasaran', 'Philip Kotler', 'Erlangga', 2016, 'Ekonomi', $lMan, 4],
            ['Ekonomi Mikro', 'N. Gregory Mankiw', 'Salemba Empat', 2014, 'Ekonomi', $lMan, 4],
            ['Statistika untuk Ekonomi dan Bisnis', 'Suharyadi', 'Salemba Empat', 2012, 'Ekonomi', $lMan, 4],
            ['Kewirausahaan', 'Suryana', 'Salemba Empat', 2013, 'Ekonomi', $lMan, 4],
            ['Kalkulus Jilid 1', 'Purcell dan Varberg', 'Erlangga', 2010, 'Sains', $lUmum, 6],
            ['Kalkulus Jilid 2', 'Purcell dan Varberg', 'Erlangga', 2010, 'Sains', $lUmum, 5],
            ['Aljabar Linear Elementer', 'Howard Anton', 'Erlangga', 2011, 'Sains', $lUmum, 5],
            ['Fisika Dasar 1', 'Halliday dan Resnick', 'Erlangga', 2010, 'Sains', $lUmum, 5],
            ['Kimia Dasar', 'Raymond Chang', 'Erlangga', 2011, 'Sains', $lUmum, 4],
            ['Matematika Diskrit', 'Rinaldi Munir', 'Informatika', 2012, 'Sains', $lUmum, 5],
            ['Statistika dan Probabilitas', 'Murray R. Spiegel', 'Erlangga', 2009, 'Sains', $lUmum, 4],
            ['English for Information Technology', 'Maja Olejniczak', 'Pearson Longman', 2010, 'Bahasa', $lUmum, 4],
            ['Bahasa Indonesia untuk Perguruan Tinggi', 'Tim Penyusun', 'Rajawali Pers', 2016, 'Bahasa', $lUmum, 6],
            ['Pendidikan Pancasila', 'Kaelan', 'Paradigma', 2013, 'Sosial', $lUmum, 5],
            ['Pendidikan Kewarganegaraan', 'Tim ICCE UIN Jakarta', 'Kencana', 2012, 'Sosial', $lUmum, 5],
            ['Metodologi Penelitian Kuantitatif dan Kualitatif', 'Sugiyono', 'Alfabeta', 2019, 'Pendidikan', $lRef, 5],
            ['Panduan Penulisan Karya Ilmiah', 'Tim Penyusun', 'Rajawali Pers', 2017, 'Pendidikan', $lRef, 4],
            ['Kamus Besar Bahasa Indonesia', 'Badan Bahasa', 'Balai Pustaka', 2016, 'Pendidikan', $lRef, 2],
            ['Ensiklopedia Teknologi Informasi', 'Tim Penyusun', 'Informatika', 2015, 'Teknologi', $lRef, 2],
        ];

        $idBuku = [];
        foreach ($buku as $i => [$judul, $penulis, $penerbit, $tahun, $k, $l, $stok]) {
            $isbn = '97860200'.str_pad((string) (100 + $i), 5, '0', STR_PAD_LEFT);
            $ada = DB::table('buku')->where('isbn', $isbn)->orWhere('judul', $judul)->value('id');
            $idBuku[$i] = $ada ?? DB::table('buku')->insertGetId([
                'kategori_id' => $kat($k),
                'lokasi_id' => $l,
                'tipe_koleksi_id' => $l === $lRef ? $tRef : $tBuku,
                'judul' => $judul, 'penulis' => $penulis, 'penerbit' => $penerbit,
                'tahun_terbit' => $tahun, 'isbn' => $isbn, 'stok' => $stok, 'cover' => null,
            ]);
        }

        // ---------- PENGGUNA ----------
        $jMhs = $this->ambil('jenis_user', 'nama_role', 'Mahasiswa');
        $jDosen = $this->ambil('jenis_user', 'nama_role', 'Dosen');
        $pw = Hash::make('password123');

        $dosen = ['Dr. Ir. Zulkifli, M.T.', 'Hendra Saputra, S.T., M.Kom.', 'Nurul Hidayah, S.Kom., M.T.', 'Ir. Teuku Rizki, M.Sc.', 'Cut Marlina, S.E., M.Si.'];
        $mhs = [
            'Muhammad Rizky Pratama', 'Nurul Aini', 'Teuku Fajar Maulana', 'Cut Nabila Putri', 'Ahmad Zikri',
            'Putri Amelia', 'Rahmat Hidayat', 'Siti Maryam', 'Muhammad Faisal', 'Dewi Safitri',
            'Zulfikar Akbar', 'Intan Permata Sari', 'Fadhil Ramadhan', 'Rina Wahyuni', 'Teuku Iqbal',
            'Nadia Khairunnisa', 'Syahrul Gunawan', 'Maulida Fitri', 'Irfan Hakim', 'Cut Aisyah',
            'Reza Pahlevi', 'Sari Mulyani', 'Dedi Kurniawan', 'Yuliana Sari', 'Muhammad Haikal',
            'Elsa Marlina', 'Rizal Fahmi', 'Wulandari', 'Ilham Syahputra', 'Nur Azizah',
            'Bayu Anggara', 'Chairunnisa', 'Taufik Hidayat', 'Fitria Ramadani', 'Habibi Rahman',
            'Lisa Andriani', 'Ridwan Saleh', 'Mega Lestari', 'Andi Firmansyah', 'Raisa Ananda',
            'Joko Susilo', 'Salsabila', 'Ikhsan Nasution', 'Dina Mariana', 'Yusuf Ibrahim',
            'Aulia Rahmi', 'Kemal Pasha', 'Hana Pertiwi', 'Syafrizal', 'Cut Intan Zahara',
        ];

        $idDosen = [];
        foreach ($dosen as $i => $nama) {
            $nip = '19800101200501'.str_pad((string) (1001 + $i), 4, '0', STR_PAD_LEFT);
            $idDosen[] = $this->user($jDosen, $nama, $nip, 'dosen'.($i + 1).'@pnl.ac.id', $pw);
        }
        $idMhs = [];
        foreach ($mhs as $i => $nama) {
            $nim = '2025573020'.str_pad((string) (101 + $i), 3, '0', STR_PAD_LEFT);
            $email = 'mhs'.($i + 1).'@mhs.pnl.ac.id';
            $idMhs[] = $this->user($jMhs, $nama, $nim, $email, $pw);
        }

        // ---------- TUGAS AKHIR (berkas PDF sengaja kosong; diunggah/diganti lewat menu petugas) ----------
        $judulTA = [
            'Rancang Bangun Sistem Informasi Perpustakaan Berbasis Web dengan Laravel',
            'Sistem Penjadwalan Online Puskesmas Menggunakan Framework Laravel',
            'Aplikasi Absensi Mahasiswa Berbasis QR Code',
            'Implementasi Face Recognition untuk Sistem Keamanan Laptop',
            'Rancang Bangun Sistem Monitoring Suhu Ruangan Berbasis IoT',
            'Sistem Pakar Diagnosa Penyakit Tanaman Padi dengan Metode Forward Chaining',
            'Aplikasi E-Commerce Hasil Pertanian Lokal Aceh Utara',
            'Analisis Keamanan Jaringan Wi-Fi Kampus Menggunakan Metode Penetration Testing',
            'Implementasi Load Balancing pada Jaringan MikroTik',
            'Sistem Informasi Akademik Berbasis Web pada SMK Negeri',
            'Rancang Bangun Sistem Pendeteksi Banjir Berbasis Arduino',
            'Klasifikasi Sentimen Media Sosial Menggunakan Naive Bayes',
            'Aplikasi Pelaporan Kerusakan Fasilitas Kampus Berbasis Web',
            'Sistem Informasi Penjualan dan Stok Barang pada Toko Bangunan',
            'Perancangan Aplikasi Mobile Pembelajaran Bahasa Inggris',
            'Rancang Bangun Alat Penyortir Buah Otomatis Berbasis Mikrokontroler',
            'Analisis Kekuatan Tarik Baja Karbon Rendah Hasil Pengelasan SMAW',
            'Perancangan Mesin Pencacah Sampah Plastik Skala Rumah Tangga',
            'Pengaruh Variasi Putaran Spindel terhadap Kekasaran Permukaan Hasil Bubut',
            'Studi Efisiensi Mesin Pendingin Menggunakan Refrigeran Hidrokarbon',
            'Perancangan Sistem Pembangkit Listrik Tenaga Surya Skala Kecil',
            'Rancang Bangun Panel Kontrol Pompa Air Otomatis Berbasis PLC',
            'Analisis Jatuh Tegangan pada Jaringan Distribusi 20 kV',
            'Perancangan Sistem Proteksi Gardu Distribusi',
            'Analisis Stabilitas Lereng pada Ruas Jalan Lintas Lhokseumawe',
            'Perencanaan Struktur Gedung Dua Lantai dengan Beton Bertulang',
            'Analisis Campuran Beton Menggunakan Abu Sekam Padi',
            'Perencanaan Drainase Perkotaan Kawasan Kampus',
            'Evaluasi Kinerja Simpang Bersinyal di Kota Lhokseumawe',
            'Analisis Laporan Keuangan dalam Menilai Kinerja Perusahaan Daerah',
            'Penerapan Sistem Informasi Akuntansi pada Usaha Kecil Menengah',
            'Pengaruh Kualitas Pelayanan terhadap Kepuasan Pelanggan',
            'Analisis Harga Pokok Produksi pada Industri Kerupuk Rumahan',
            'Sistem Informasi Penggajian Karyawan Berbasis Web',
            'Pengembangan Website Profil Desa untuk Promosi Wisata',
            'Aplikasi Reservasi Ruang Diskusi Perpustakaan',
            'Sistem Pendukung Keputusan Pemilihan Beasiswa Metode SAW',
            'Deteksi Objek Real-Time Menggunakan YOLO untuk Pemantauan Parkir',
            'Pengembangan Chatbot Layanan Akademik Berbasis Telegram',
            'Rancang Bangun Sistem Presensi Berbasis RFID',
            'Optimasi Basis Data Perpustakaan dengan Teknik Indexing MySQL',
            'Analisis Performa Server Web Nginx dan Apache',
            'Penerapan VLAN dan Inter-VLAN Routing pada Jaringan Laboratorium',
            'Aplikasi Katalog Digital Produk UMKM Kuliner',
            'Rancang Bangun Sistem Parkir Cerdas Berbasis Sensor Ultrasonik',
            'Sistem Informasi Geografis Sebaran Fasilitas Kesehatan',
            'Perancangan Dashboard Visualisasi Data Kunjungan Perpustakaan',
            'Pemanfaatan Limbah Kelapa Sawit sebagai Bahan Bakar Alternatif',
            'Perancangan Alat Pengering Ikan Tenaga Surya',
            'Analisis Getaran pada Poros Motor Listrik',
            'Rancang Bangun Sistem Irigasi Otomatis Berbasis Sensor Kelembapan Tanah',
            'Sistem Keamanan Pintu Menggunakan Sidik Jari dan Arduino',
            'Evaluasi Usability Website Kampus dengan Metode SUS',
            'Penerapan Metode Scrum pada Pengembangan Aplikasi Inventaris',
            'Perancangan Sistem Informasi Donasi Masjid Berbasis Web',
            'Analisis Pengendalian Persediaan Bahan Baku dengan Metode EOQ',
        ];

        $punyaReviewer = Schema::hasColumn('tugas_akhir', 'reviewer_id');
        $reviewer = $punyaReviewer ? $this->petugasId() : null;
        $baru = 0;
        foreach ($judulTA as $i => $judul) {
            if (DB::table('tugas_akhir')->where('judul', $judul)->exists()) {
                continue;
            }
            $row = [
                'user_id' => $idMhs[$i % count($idMhs)],
                'judul' => $judul,
                'pembimbing' => $dosen[$i % count($dosen)],
                'file_tugas' => null,
                'status' => $i % 11 === 10 ? 'Menunggu' : 'Disetujui',
            ];
            if ($punyaReviewer && $row['status'] === 'Disetujui') {
                $row['reviewer_id'] = $reviewer;
            }
            DB::table('tugas_akhir')->insert($row);
            $baru++;
        }

        // ---------- PEMINJAMAN ----------
        $pinjam = 0;
        if (DB::table('peminjaman')->whereIn('user_id', $idMhs)->count() < 60) {
            for ($i = 0; $i < 60; $i++) {
                $uid = $idMhs[($i * 7) % count($idMhs)];
                $acak = mt_rand(1, 100);
                $kembali = null;

                if ($acak <= 8) {
                    $status = 'Menunggu';
                    $pjm = $hariIni->copy()->subDays(mt_rand(0, 3));
                    $tempo = null;
                } elseif ($acak <= 14) {
                    $status = 'Ditolak';
                    $pjm = $hariIni->copy()->subDays(mt_rand(5, 60));
                    $tempo = null;
                } elseif ($acak <= 52) {
                    $status = 'Dikembalikan';
                    $pjm = $hariIni->copy()->subDays(mt_rand(20, 150));
                    $tempo = $pjm->copy()->addDays(90);
                    $kembali = $pjm->copy()->addDays(mt_rand(5, 110));
                    if ($kembali->gt($hariIni)) {
                        $kembali = $hariIni->copy()->subDays(mt_rand(0, 3));
                    }
                } else {
                    $status = 'Dipinjam';
                    // sebagian sudah lewat jatuh tempo (tampil Terlambat)
                    $pjm = $hariIni->copy()->subDays($acak > 80 ? mt_rand(95, 150) : mt_rand(1, 60));
                    $tempo = $pjm->copy()->addDays(90);
                }

                $pid = DB::table('peminjaman')->insertGetId([
                    'user_id' => $uid,
                    'tanggal_pinjam' => $pjm->toDateString(),
                    'tanggal_kembali' => $kembali?->toDateString(),
                    'status' => $status,
                    'tanggal_jatuh_tempo' => $tempo?->toDateString(),
                ]);

                foreach ((array) array_rand($idBuku, mt_rand(1, 2)) as $b) {
                    DB::table('detail_peminjaman')->insert(['peminjaman_id' => $pid, 'buku_id' => $idBuku[$b], 'jumlah' => 1]);
                    if ($status === 'Dipinjam') {
                        DB::table('buku')->where('id', $idBuku[$b])->where('stok', '>', 0)->decrement('stok');
                    }
                }

                // Denda keterlambatan untuk buku yang dikembalikan setelah jatuh tempo
                if ($status === 'Dikembalikan' && $kembali->gt($tempo)) {
                    $hari = (int) $tempo->diffInDays($kembali);
                    $lunas = mt_rand(0, 1) === 1;
                    DB::table('denda')->insert([
                        'peminjaman_id' => $pid, 'detail_peminjaman_id' => null, 'jenis' => 'terlambat',
                        'hari_terlambat' => $hari, 'tarif_per_hari' => 1000, 'jumlah_denda' => $hari * 1000,
                        'status_bayar' => $lunas ? 'Lunas' : 'Belum Lunas',
                        'tanggal_bayar' => $lunas ? $kembali->toDateString() : null,
                        'keterangan' => "Terlambat {$hari} hari", 'created_at' => $kembali,
                    ]);
                }
                $pinjam++;
            }
        }

        $this->command?->info(count($idBuku).' buku, '.(count($idMhs) + count($idDosen))." pengguna, {$baru} tugas akhir baru, {$pinjam} peminjaman baru. Password akun contoh: password123");
    }

    /** Cari baris berdasarkan nama; buat bila belum ada. Mengembalikan id. */
    private function ambil(string $tabel, string $kolom, string $nilai, array $tambahan = []): int
    {
        $id = DB::table($tabel)->where($kolom, $nilai)->value('id');
        if ($id) {
            return (int) $id;
        }
        $data = [$kolom => $nilai];
        foreach ($tambahan as $k => $v) {
            if (Schema::hasColumn($tabel, $k)) {
                $data[$k] = $v;
            }
        }

        return (int) DB::table($tabel)->insertGetId($data);
    }

    private function user(int $jenis, string $nama, string $nim, string $email, string $pw): int
    {
        $id = DB::table('user')->where('nim_nip', $nim)->orWhere('email', $email)->value('id');

        return (int) ($id ?? DB::table('user')->insertGetId([
            'jenis_user_id' => $jenis, 'nama' => $nama, 'nim_nip' => $nim, 'email' => $email, 'password' => $pw,
        ]));
    }

    private function petugasId(): ?int
    {
        $id = DB::table('user')->join('jenis_user', 'jenis_user.id', '=', 'user.jenis_user_id')
            ->whereIn('jenis_user.nama_role', ['Petugas', 'Admin'])->value('user.id');

        return $id ? (int) $id : null;
    }
}
