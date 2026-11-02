-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 11, 2026 at 04:40 PM
-- Server version: 8.4.3
-- PHP Version: 8.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web_perpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_masuk` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id`, `user_id`, `tanggal`, `waktu_masuk`) VALUES
(1, 1, '2024-09-02', '08:14:05'),
(2, 5, '2024-09-03', '10:22:19'),
(3, 14, '2024-09-03', '13:05:40'),
(4, 2, '2024-09-04', '08:45:11'),
(5, 1, '2024-09-05', '09:10:33'),
(6, 8, '2024-09-06', '11:00:15'),
(7, 2, '2024-09-09', '14:20:00'),
(8, 1, '2024-09-10', '08:05:50'),
(9, 5, '2024-09-11', '09:40:12'),
(11, 1, '2024-09-16', '11:30:08'),
(12, 11, '2024-09-17', '12:50:44'),
(13, 2, '2024-09-18', '08:25:30'),
(14, 5, '2024-09-19', '13:10:05'),
(15, 1, '2024-09-20', '08:55:12'),
(16, 7, '2024-09-23', '09:05:00'),
(17, 2, '2024-09-24', '10:40:18'),
(18, 1, '2024-09-25', '14:00:52'),
(19, 5, '2024-09-26', '11:15:30'),
(21, 2, '2024-09-30', '09:20:45'),
(22, 1, '2024-10-01', '08:10:00'),
(23, 1, '2024-10-02', '10:05:22'),
(24, 5, '2024-10-03', '08:50:11'),
(25, 2, '2024-10-04', '13:30:15');

-- --------------------------------------------------------

--
-- Table structure for table `artikel`
--

CREATE TABLE `artikel` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `kategori_id` int NOT NULL,
  `judul` varchar(250) NOT NULL,
  `isi_artikel` text,
  `tanggal_upload` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `artikel`
--

INSERT INTO `artikel` (`id`, `user_id`, `kategori_id`, `judul`, `isi_artikel`, `tanggal_upload`) VALUES
(1, 1, 1, 'Perkembangan Teknologi Informasi di Era Digital', 'Perkembangan teknologi informasi memberikan banyak perubahan dalam kehidupan masyarakat dan dunia pendidikan.', '2026-08-01'),
(2, 2, 1, 'Mengenal Sistem Informasi Berbasis Web', 'Sistem informasi berbasis web membantu organisasi dalam mengelola dan menyampaikan informasi secara cepat.', '2026-08-03'),
(3, 3, 6, 'Metode Belajar Efektif bagi Mahasiswa', 'Mahasiswa dapat meningkatkan hasil belajar dengan mengatur waktu dan menggunakan sumber belajar yang tepat.', '2026-08-05'),
(4, 4, 7, 'Peran Mahasiswa dalam Masyarakat', 'Mahasiswa memiliki peran penting dalam memberikan kontribusi positif kepada masyarakat melalui ilmu pengetahuan.', '2026-08-07'),
(5, 5, 8, 'Pengelolaan Keuangan Pribadi Mahasiswa', 'Pengelolaan keuangan yang baik membantu mahasiswa memenuhi kebutuhan tanpa mengabaikan perencanaan masa depan.', '2026-08-09'),
(6, 6, 9, 'Pentingnya Literasi Sains', 'Literasi sains membantu seseorang memahami berbagai fenomena dan mengambil keputusan berdasarkan informasi.', '2026-08-11'),
(7, 7, 1, 'Keamanan Data dalam Sistem Informasi', 'Keamanan data menjadi bagian penting dalam pengembangan sistem informasi modern.', '2026-08-13'),
(8, 8, 6, 'Pemanfaatan Teknologi untuk Pendidikan', 'Teknologi dapat digunakan untuk mendukung proses pembelajaran dan memperluas akses terhadap sumber pendidikan.', '2026-08-15'),
(9, 9, 7, 'Media Sosial dan Kehidupan Mahasiswa', 'Media sosial dapat memberikan manfaat dalam komunikasi, tetapi penggunaannya perlu dilakukan secara bijak.', '2026-08-17'),
(10, 10, 8, 'Ekonomi Digital di Indonesia', 'Ekonomi digital berkembang seiring dengan meningkatnya penggunaan internet dan teknologi dalam kegiatan ekonomi.', '2026-08-19'),
(11, 11, 9, 'Teknologi dalam Penelitian Sains', 'Teknologi membantu peneliti dalam mengumpulkan, mengolah, dan menganalisis data penelitian.', '2026-08-21'),
(12, 12, 1, 'Dasar-Dasar Pemrograman untuk Pemula', 'Pemrograman merupakan salah satu keterampilan yang banyak digunakan dalam pengembangan aplikasi dan sistem informasi.', '2026-08-23'),
(13, 13, 6, 'Perpustakaan sebagai Sumber Pembelajaran', 'Perpustakaan menyediakan berbagai sumber informasi yang dapat mendukung kegiatan akademik mahasiswa.', '2026-08-25'),
(14, 14, 7, 'Budaya Membaca di Kalangan Mahasiswa', 'Kebiasaan membaca dapat membantu mahasiswa memperluas wawasan dan meningkatkan kemampuan berpikir kritis.', '2026-08-27'),
(15, 15, 8, 'Manajemen Waktu untuk Mahasiswa', 'Manajemen waktu membantu mahasiswa menyeimbangkan kegiatan akademik dan aktivitas lainnya.', '2026-08-29');

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id` int NOT NULL,
  `kategori_id` int NOT NULL,
  `lokasi_id` int NOT NULL,
  `tipe_koleksi_id` int NOT NULL,
  `judul` varchar(200) NOT NULL,
  `penulis` varchar(150) DEFAULT NULL,
  `penerbit` varchar(150) DEFAULT NULL,
  `tahun_terbit` year DEFAULT NULL,
  `isbn` varchar(50) DEFAULT NULL,
  `stok` int DEFAULT '0',
  `cover` varchar(400) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id`, `kategori_id`, `lokasi_id`, `tipe_koleksi_id`, `judul`, `penulis`, `penerbit`, `tahun_terbit`, `isbn`, `stok`, `cover`) VALUES
(1, 1, 7, 8, 'Pemrograman Web dengan Laravel', 'Abdul Kadir', 'Andi Publisher', '2023', '9786020000011', 5, NULL),
(2, 1, 8, 8, 'Basis Data MySQL untuk Pemula', 'Budi Raharjo', 'Informatika', '2022', '9786020000012', 4, NULL),
(3, 1, 7, 1, 'Sistem Informasi Perpustakaan Berbasis Web', 'Ahmad Fauzi', 'Gramedia', '2024', '9786020000013', 3, NULL),
(4, 1, 8, 2, 'Perancangan Aplikasi Akademik Berbasis Database', 'Deni Setiawan', 'Andi Publisher', '2023', '9786020000014', 2, NULL),
(5, 1, 7, 3, 'Implementasi Teknologi Informasi di Perguruan Tinggi', 'Rina Putri', 'Deepublish', '2024', '9786020000015', 3, NULL),
(6, 6, 10, 8, 'Strategi Pembelajaran Modern', 'Siti Nurhayati', 'Erlangga', '2021', '9786020000016', 6, NULL),
(7, 6, 10, 8, 'Metode Penelitian Pendidikan', 'Sugiyono', 'Alfabeta', '2022', '9786020000017', 8, NULL),
(8, 6, 4, 3, 'Pengembangan Media Pembelajaran Digital', 'Lina Marlina', 'Prenada Media', '2023', '9786020000018', 4, NULL),
(9, 6, 10, 5, 'Evaluasi Sistem Pendidikan', 'Ahmad Yani', 'Bumi Aksara', '2022', '9786020000019', 3, NULL),
(10, 6, 4, 4, 'Jurnal Pendidikan dan Teknologi', 'Tim Peneliti Pendidikan', 'Universitas Indonesia', '2024', '9786020000020', 5, NULL),
(11, 8, 9, 8, 'Manajemen Keuangan Modern', 'Kasmir', 'Rajawali Pers', '2023', '9786020000021', 7, NULL),
(12, 8, 9, 8, 'Pengantar Ilmu Ekonomi', 'Sadono Sukirno', 'Rajawali Pers', '2022', '9786020000022', 6, NULL),
(13, 8, 9, 3, 'Analisis Laporan Keuangan Perusahaan', 'Hery', 'Grasindo', '2024', '9786020000023', 4, NULL),
(14, 8, 10, 5, 'Penelitian Ekonomi Digital', 'Andi Wijaya', 'Deepublish', '2023', '9786020000024', 3, NULL),
(15, 8, 9, 4, 'Jurnal Ekonomi dan Bisnis', 'Tim Ekonomi', 'Universitas Gadjah Mada', '2024', '9786020000025', 5, NULL),
(16, 7, 10, 8, 'Sosiologi Masyarakat Modern', 'Soerjono Soekanto', 'Rajawali Pers', '2021', '9786020000026', 5, NULL),
(17, 7, 10, 8, 'Pengantar Ilmu Komunikasi', 'Deddy Mulyana', 'Remaja Rosdakarya', '2022', '9786020000027', 6, NULL),
(18, 7, 4, 3, 'Penelitian Sosial Kemasyarakatan', 'Bambang Prasetyo', 'Kencana', '2023', '9786020000028', 4, NULL),
(19, 7, 4, 5, 'Laporan Penelitian Sosial Indonesia', 'Tim Sosial', 'UI Press', '2024', '9786020000029', 3, NULL),
(20, 7, 10, 4, 'Jurnal Ilmu Sosial', 'Tim Peneliti Sosial', 'Universitas Indonesia', '2024', '9786020000030', 5, NULL),
(21, 9, 3, 8, 'Biologi Dasar untuk Mahasiswa', 'Campbell', 'Erlangga', '2021', '9786020000031', 7, NULL),
(22, 9, 3, 8, 'Kimia Dasar', 'Raymond Chang', 'McGraw Hill', '2022', '9786020000032', 5, NULL),
(23, 9, 3, 8, 'Fisika Modern', 'Young Freedman', 'Erlangga', '2023', '9786020000033', 4, NULL),
(24, 9, 5, 3, 'Penelitian Ilmu Sains Terapan', 'Taufik Hidayat', 'Deepublish', '2024', '9786020000034', 3, NULL),
(25, 9, 5, 4, 'Jurnal Sains dan Teknologi', 'Tim Sains', 'ITB Press', '2024', '9786020000035', 6, NULL),
(26, 4, 2, 8, 'Misteri Rumah Tua', 'R. Aditya', 'Gramedia', '2022', '9786020000036', 4, NULL),
(27, 4, 2, 8, 'Bayangan di Tengah Malam', 'Nadia Putri', 'Erlangga', '2023', '9786020000037', 3, NULL),
(28, 4, 2, 8, 'Kasus Rahasia Kota Lama', 'Fajar Nugraha', 'Mizan', '2024', '9786020000038', 5, NULL),
(29, 3, 2, 8, 'Dunia Fantasi Ajaib', 'Alya Rahman', 'Gramedia', '2022', '9786020000039', 6, NULL),
(30, 3, 2, 8, 'Kerajaan Langit Biru', 'Dina Maharani', 'Bentang Pustaka', '2023', '9786020000040', 4, NULL),
(31, 5, 2, 9, 'Petualangan Si Kancil', 'Tono Wijaya', 'Elex Media', '2021', '9786020000041', 8, NULL),
(32, 5, 2, 9, 'Komik Edukasi Sains', 'Rudi Hartono', 'Elex Media', '2022', '9786020000042', 6, NULL),
(33, 5, 2, 9, 'Komik Sejarah Indonesia', 'Andika Putra', 'Gramedia', '2023', '9786020000043', 5, NULL),
(34, 5, 2, 9, 'Dunia Robot Masa Depan', 'Kevin Lee', 'Mizan', '2024', '9786020000044', 4, NULL),
(35, 5, 2, 9, 'Petualangan Anak Pintar', 'Salsa Putri', 'Erlangga', '2023', '9786020000045', 7, NULL),
(36, 1, 5, 1, 'Skripsi Sistem Informasi Akademik', 'Miftah Artika', 'Repository Kampus', '2025', '9786020000046', 1, NULL),
(37, 1, 5, 2, 'Tugas Akhir Aplikasi Mobile Library', 'Ahmad Fauzan', 'Repository Kampus', '2025', '9786020000047', 1, NULL),
(38, 7, 4, 3, 'Karya Tulis Ilmiah Pengaruh Media Sosial', 'Siti Rahma', 'Repository Kampus', '2025', '9786020000048', 1, NULL),
(39, 9, 5, 4, 'Jurnal Penelitian Data Mining', 'Rizky Pratama', 'Repository Kampus', '2025', '9786020000049', 1, NULL),
(40, 6, 4, 5, 'Laporan Penelitian Pendidikan Digital', 'Nabila Putri', 'Repository Kampus', '2025', '9786020000050', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `denda`
--

CREATE TABLE `denda` (
  `id` int NOT NULL,
  `peminjaman_id` int NOT NULL,
  `detail_peminjaman_id` int DEFAULT NULL,
  `jenis` enum('terlambat','rusak','hilang') NOT NULL DEFAULT 'terlambat',
  `hari_terlambat` int NOT NULL DEFAULT '0',
  `tarif_per_hari` int NOT NULL DEFAULT '0',
  `jumlah_denda` int NOT NULL DEFAULT '0',
  `status_bayar` enum('Belum Lunas','Lunas') NOT NULL DEFAULT 'Belum Lunas',
  `tanggal_bayar` date DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `denda`
--

INSERT INTO `denda` (`id`, `peminjaman_id`, `detail_peminjaman_id`, `jenis`, `hari_terlambat`, `tarif_per_hari`, `jumlah_denda`, `status_bayar`, `tanggal_bayar`, `keterangan`, `created_at`) VALUES
(1, 5, NULL, 'terlambat', 12, 1000, 12000, 'Belum Lunas', NULL, 'Terlambat 12 hari', '2026-10-04 19:31:42'),
(2, 7, NULL, 'terlambat', 17, 1000, 17000, 'Lunas', '2026-09-30', 'Terlambat 17 hari', '2026-10-04 19:31:42'),
(3, 3, 3, 'rusak', 0, 0, 25000, 'Lunas', '2026-09-12', 'Halaman buku sobek', '2026-10-04 19:31:42'),
(4, 10, 10, 'hilang', 0, 0, 75000, 'Belum Lunas', NULL, 'Buku hilang, ganti sesuai harga', '2026-10-04 19:31:42');

-- --------------------------------------------------------

--
-- Table structure for table `detail_peminjaman`
--

CREATE TABLE `detail_peminjaman` (
  `id` int NOT NULL,
  `peminjaman_id` int NOT NULL,
  `buku_id` int NOT NULL,
  `jumlah` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detail_peminjaman`
--

INSERT INTO `detail_peminjaman` (`id`, `peminjaman_id`, `buku_id`, `jumlah`) VALUES
(1, 1, 1, 1),
(2, 2, 2, 1),
(3, 3, 3, 1),
(4, 4, 4, 1),
(5, 5, 5, 1),
(6, 6, 6, 1),
(7, 7, 7, 1),
(8, 8, 8, 1),
(9, 9, 9, 1),
(10, 10, 10, 1),
(11, 11, 11, 1),
(12, 12, 12, 1),
(13, 13, 13, 1),
(14, 14, 14, 1),
(15, 15, 15, 1),
(18, 21, 35, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ejurnal`
--

CREATE TABLE `ejurnal` (
  `id` int NOT NULL,
  `kategori_id` int NOT NULL,
  `judul` varchar(200) NOT NULL,
  `penulis` varchar(150) DEFAULT NULL,
  `abstrak` text,
  `file_jurnal` varchar(255) DEFAULT NULL,
  `tahun_terbit` year DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ejurnal`
--

INSERT INTO `ejurnal` (`id`, `kategori_id`, `judul`, `penulis`, `abstrak`, `file_jurnal`, `tahun_terbit`) VALUES
(1, 1, 'Implementasi Sistem Informasi Perpustakaan Berbasis Web', 'Andi Setiawan', 'Penelitian ini membahas implementasi sistem informasi perpustakaan berbasis web untuk meningkatkan efisiensi pengelolaan data.', 'ejurnal/sistem_informasi_perpustakaan.pdf', '2026'),
(2, 1, 'Analisis Keamanan Aplikasi Web', 'Rina Permata', 'Penelitian membahas analisis keamanan aplikasi web dan beberapa ancaman yang dapat terjadi.', 'ejurnal/keamanan_aplikasi_web.pdf', '2026'),
(3, 6, 'Pemanfaatan Teknologi Digital dalam Pembelajaran', 'Dewi Lestari', 'Penelitian membahas pemanfaatan teknologi digital untuk mendukung proses pembelajaran mahasiswa.', 'ejurnal/teknologi_pembelajaran.pdf', '2025'),
(4, 6, 'Pengembangan Media Pembelajaran Interaktif', 'Budi Santoso', 'Penelitian membahas pengembangan media pembelajaran interaktif untuk meningkatkan keterlibatan peserta didik.', 'ejurnal/media_pembelajaran.pdf', '2025'),
(5, 7, 'Pengaruh Media Sosial terhadap Mahasiswa', 'Siti Rahma', 'Penelitian menganalisis penggunaan media sosial dan pengaruhnya terhadap aktivitas mahasiswa.', 'ejurnal/media_sosial_mahasiswa.pdf', '2025'),
(6, 8, 'Analisis Ekonomi Digital', 'Fajar Nugraha', 'Penelitian membahas perkembangan ekonomi digital dan pengaruh teknologi terhadap aktivitas ekonomi.', 'ejurnal/ekonomi_digital.pdf', '2026'),
(7, 9, 'Penerapan Data Mining dalam Pendidikan', 'Rizky Pratama', 'Penelitian membahas penggunaan data mining untuk membantu analisis data pendidikan.', 'ejurnal/data_mining_pendidikan.pdf', '2025'),
(8, 1, 'Perancangan Database Sistem Akademik', 'Ahmad Fauzan', 'Penelitian membahas perancangan database untuk mendukung sistem informasi akademik.', 'ejurnal/database_akademik.pdf', '2025'),
(9, 6, 'Sistem Pembelajaran Berbasis Teknologi', 'Nabila Putri', 'Penelitian membahas penerapan sistem pembelajaran berbasis teknologi di perguruan tinggi.', 'ejurnal/sistem_pembelajaran.pdf', '2024'),
(10, 7, 'Perilaku Digital Generasi Muda', 'Maya Anggraini', 'Penelitian menganalisis perilaku generasi muda dalam menggunakan teknologi digital.', 'ejurnal/perilaku_digital.pdf', '2024'),
(11, 8, 'Strategi Bisnis pada Era Digital', 'Dimas Saputra', 'Penelitian membahas strategi bisnis yang dapat diterapkan pada era transformasi digital.', 'ejurnal/strategi_bisnis_digital.pdf', '2025'),
(12, 9, 'Analisis Data untuk Penelitian Ilmiah', 'Putri Amelia', 'Penelitian membahas penggunaan teknik analisis data untuk mendukung penelitian ilmiah.', 'ejurnal/analisis_data_ilmiah.pdf', '2026'),
(13, 1, 'Pengembangan Aplikasi Mobile', 'Yoga Prasetyo', 'Penelitian membahas proses pengembangan aplikasi mobile dan kebutuhan penggunanya.', 'ejurnal/pengembangan_aplikasi_mobile.pdf', '2025'),
(14, 6, 'Evaluasi Pembelajaran Online', 'Citra Maharani', 'Penelitian membahas evaluasi pelaksanaan pembelajaran online di lingkungan perguruan tinggi.', 'ejurnal/evaluasi_pembelajaran_online.pdf', '2024'),
(15, 7, 'Literasi Digital Mahasiswa', 'Arif Hidayat', 'Penelitian membahas tingkat literasi digital mahasiswa dalam menggunakan teknologi informasi.', 'ejurnal/literasi_digital.pdf', '2025'),
(16, 8, 'Perkembangan Fintech di Indonesia', 'Rani Oktaviani', 'Penelitian membahas perkembangan teknologi finansial dan penggunaannya dalam masyarakat.', 'ejurnal/perkembangan_fintech.pdf', '2026'),
(17, 9, 'Penerapan Kecerdasan Buatan dalam Pendidikan', 'Galang Prakoso', 'Penelitian membahas potensi penerapan kecerdasan buatan untuk mendukung pendidikan.', 'ejurnal/ai_pendidikan.pdf', '2026'),
(18, 1, 'Analisis User Interface Aplikasi Web', 'Vina Safitri', 'Penelitian membahas analisis antarmuka pengguna untuk meningkatkan kemudahan penggunaan aplikasi.', 'ejurnal/user_interface_web.pdf', '2025'),
(19, 6, 'Teknologi Informasi untuk Perpustakaan', 'Laila Khairunnisa', 'Penelitian membahas pemanfaatan teknologi informasi dalam meningkatkan layanan perpustakaan.', 'ejurnal/teknologi_perpustakaan.pdf', '2026'),
(20, 9, 'Pengolahan Data Penelitian Menggunakan Komputer', 'Raka Firmansyah', 'Penelitian membahas pemanfaatan komputer dalam proses pengolahan data penelitian.', 'ejurnal/pengolahan_data.pdf', '2024');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` int NOT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `lokasi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `kuota` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `judul`, `deskripsi`, `tanggal_mulai`, `tanggal_selesai`, `lokasi`, `kuota`) VALUES
(1, 'Seminar Literasi Digital Mahasiswa', NULL, '2026-09-10', NULL, '', 100),
(2, 'Pelatihan Penggunaan Perpustakaan Digital', NULL, '2026-09-15', NULL, '', 50),
(3, 'Workshop Penulisan Karya Ilmiah', NULL, '2026-09-20', NULL, '', 75),
(4, 'Sosialisasi Repository Tugas Akhir Mahasiswa', NULL, '2026-09-25', NULL, '', 100),
(5, 'Pelatihan Mencari Referensi Jurnal Nasional dan Internasional', NULL, '2026-10-01', NULL, '', 60),
(6, 'Seminar Manajemen Referensi Menggunakan Mendeley', NULL, '2026-10-05', NULL, '', 80),
(7, 'Diskusi Ilmiah Mahasiswa', NULL, '2026-10-10', NULL, '', 50),
(8, 'Pelatihan Penyusunan Skripsi', NULL, '2026-10-15', NULL, '', 100),
(9, 'Pameran Karya Tulis Mahasiswa', NULL, '2026-10-20', NULL, '', 120),
(10, 'Gerakan Membaca Bersama di Perpustakaan', NULL, '2026-10-25', NULL, '', 150);

-- --------------------------------------------------------

--
-- Table structure for table `event_peserta`
--

CREATE TABLE `event_peserta` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `events_id` int NOT NULL,
  `status_pendaftaran` varchar(50) DEFAULT 'Terdaftar',
  `tanggal_daftar` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `event_peserta`
--

INSERT INTO `event_peserta` (`id`, `user_id`, `events_id`, `status_pendaftaran`, `tanggal_daftar`) VALUES
(1, 1, 1, 'Terdaftar', NULL),
(2, 2, 1, 'Terdaftar', NULL),
(3, 3, 2, 'Terdaftar', NULL),
(4, 4, 2, 'Hadir', NULL),
(5, 5, 3, 'Terdaftar', NULL),
(6, 6, 3, 'Hadir', NULL),
(7, 7, 4, 'Terdaftar', NULL),
(8, 8, 4, 'Hadir', NULL),
(9, 9, 5, 'Terdaftar', NULL),
(10, 10, 5, 'Terdaftar', NULL),
(11, 11, 6, 'Terdaftar', NULL),
(12, 12, 6, 'Hadir', NULL),
(13, 13, 7, 'Terdaftar', NULL),
(14, 14, 7, 'Hadir', NULL),
(15, 15, 8, 'Terdaftar', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `jenis` varchar(100) DEFAULT NULL,
  `isi` text,
  `balasan` text,
  `status` varchar(50) DEFAULT 'Belum Dibalas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `jenis`, `isi`, `balasan`, `status`) VALUES
(1, 1, NULL, 'Pelayanan perpustakaan sudah baik, tetapi koleksi buku terbaru masih perlu ditambah.', '2026-09-01', 'Belum Dibaca'),
(2, 2, NULL, 'Website perpustakaan mudah digunakan, namun pencarian karya ilmiah perlu dibuat lebih cepat.', '2026-09-03', 'Sudah Dibaca'),
(3, 3, NULL, 'Mohon ditambahkan lebih banyak koleksi skripsi dan jurnal digital.', '2026-09-05', 'Belum Dibaca'),
(4, 4, NULL, 'Fasilitas ruang baca sudah nyaman dan bersih.', '2026-09-07', 'Sudah Dibaca'),
(5, 5, NULL, 'Sistem peminjaman online sangat membantu mahasiswa.', '2026-09-10', 'Sudah Dibaca'),
(6, 6, NULL, 'Akan lebih baik jika tersedia notifikasi pengembalian buku.', '2026-09-12', 'Belum Dibaca'),
(7, 7, NULL, 'Tampilan aplikasi perpustakaan cukup menarik dan mudah dipahami.', '2026-09-15', 'Sudah Dibaca'),
(8, 8, NULL, 'Tambahkan fitur rekomendasi buku berdasarkan minat pengguna.', '2026-09-18', 'Belum Dibaca'),
(9, 9, NULL, 'Proses pencarian karya akhir mahasiswa sudah cukup membantu.', '2026-09-20', 'Sudah Dibaca'),
(10, 10, NULL, 'Perlu peningkatan jumlah komputer untuk akses perpustakaan digital.', '2026-09-22', 'Belum Dibaca');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_user`
--

CREATE TABLE `jenis_user` (
  `id` int NOT NULL,
  `nama_role` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `lama_pinjam_hari` int NOT NULL DEFAULT '90',
  `denda_per_hari` int NOT NULL DEFAULT '1000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jenis_user`
--

INSERT INTO `jenis_user` (`id`, `nama_role`, `lama_pinjam_hari`, `denda_per_hari`) VALUES
(1, 'Admin', 90, 1000),
(2, 'Mahasiswa', 90, 1000),
(3, 'Dosen', 180, 1000),
(4, 'Petugas', 90, 1000);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`) VALUES
(1, 'Teknologi'),
(2, 'Romance'),
(3, 'Fantasi'),
(4, 'Thriller'),
(5, 'Komik'),
(6, 'Pendidikan'),
(7, 'Sosial'),
(8, 'Ekonomi'),
(9, 'Sains');

-- --------------------------------------------------------

--
-- Table structure for table `lokasi`
--

CREATE TABLE `lokasi` (
  `id` int NOT NULL,
  `nama_ruang` varchar(100) NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lokasi`
--

INSERT INTO `lokasi` (`id`, `nama_ruang`, `keterangan`) VALUES
(1, 'Ruang Koleksi Skripsi', 'Tempat penyimpanan skripsi mahasiswa'),
(2, 'Ruang Koleksi Buku Umum', 'Tempat penyimpanan buku umum perpustakaan'),
(3, 'Ruang Referensi', 'Tempat penyimpanan buku referensi dan jurnal'),
(4, 'Ruang Karya Ilmiah', 'Tempat penyimpanan karya tulis ilmiah mahasiswa'),
(5, 'Ruang Digital Library', 'Tempat penyimpanan koleksi digital perpustakaan'),
(6, 'Ruang Arsip Lama', 'Tempat penyimpanan dokumen dan koleksi lama'),
(7, 'Rak A1 Lantai 1', 'Koleksi bidang teknologi informasi dan komputer'),
(8, 'Rak A2 Lantai 1', 'Koleksi sistem informasi dan basis data'),
(9, 'Rak B1 Lantai 1', 'Koleksi manajemen dan ekonomi'),
(10, 'Rak B2 Lantai 1', 'Koleksi pendidikan dan ilmu sosial');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_09_27_100000_drop_users_table', 2),
(5, '2026_09_27_100001_create_jenis_user_table', 2),
(6, '2026_09_27_100002_add_profile_fields_to_users_table', 2),
(7, '2026_09_27_100003_create_kategori_table', 2),
(8, '2026_09_27_100004_create_lokasi_table', 2),
(9, '2026_09_27_100005_create_tipe_koleksi_table', 2),
(10, '2026_09_27_100006_create_buku_table', 2),
(11, '2026_09_27_100007_create_peminjaman_table', 2),
(12, '2026_09_27_100008_create_detail_peminjaman_table', 2),
(13, '2026_09_27_100009_create_reservasi_table', 2),
(14, '2026_09_27_100010_create_absensi_table', 2),
(15, '2026_09_27_100011_create_ejurnal_table', 2),
(16, '2026_09_27_100012_create_feedback_table', 2),
(17, '2026_09_27_100013_create_event_table', 2),
(18, '2026_09_27_100014_create_event_peserta_table', 2),
(19, '2026_09_27_100015_create_tugas_akhir_table', 2),
(20, '2026_09_27_100016_create_artikel_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Dipinjam',
  `tanggal_jatuh_tempo` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `user_id`, `tanggal_pinjam`, `tanggal_kembali`, `status`, `tanggal_jatuh_tempo`) VALUES
(1, 1, '2026-09-01', '2026-09-08', 'Dikembalikan', '2026-11-30'),
(2, 2, '2026-09-02', '2026-09-09', 'Dikembalikan', '2026-12-01'),
(3, 3, '2026-09-05', '2026-09-12', 'Dikembalikan', '2026-12-04'),
(4, 4, '2026-06-01', NULL, 'Dipinjam', '2026-08-30'),
(5, 5, '2026-06-10', '2026-09-20', 'Dikembalikan', '2026-09-08'),
(6, 6, '2026-09-12', '2026-09-19', 'Dikembalikan', '2026-12-11'),
(7, 7, '2026-06-15', '2026-09-30', 'Dikembalikan', '2026-09-13'),
(8, 8, '2026-09-15', '2026-09-22', 'Dikembalikan', '2026-12-14'),
(9, 9, '2026-09-18', NULL, 'Dipinjam', '2026-12-17'),
(10, 10, '2026-09-20', '2026-09-27', 'Dikembalikan', '2026-12-19'),
(11, 11, '2026-09-21', NULL, 'Dipinjam', '2026-12-20'),
(12, 12, '2026-09-22', '2026-09-29', 'Dikembalikan', '2026-12-21'),
(13, 13, '2026-09-23', NULL, 'Dipinjam', '2026-12-22'),
(14, 14, '2026-09-24', '2026-10-01', 'Dikembalikan', '2026-12-23'),
(15, 15, '2026-09-25', NULL, 'Dipinjam', '2026-12-24'),
(21, 2, '2026-10-09', NULL, 'Menunggu', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reservasi`
--

CREATE TABLE `reservasi` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `buku_id` int NOT NULL,
  `tanggal_reservasi` date NOT NULL,
  `status` varchar(50) DEFAULT 'Menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reservasi`
--

INSERT INTO `reservasi` (`id`, `user_id`, `buku_id`, `tanggal_reservasi`, `status`) VALUES
(1, 1, 1, '2026-09-01', 'Selesai'),
(2, 2, 2, '2026-09-02', 'Selesai'),
(3, 3, 3, '2026-09-03', 'Selesai'),
(4, 4, 4, '2026-09-04', 'Diproses'),
(5, 5, 5, '2026-09-05', 'Menunggu'),
(6, 6, 6, '2026-09-06', 'Selesai'),
(7, 7, 7, '2026-09-07', 'Selesai'),
(8, 8, 8, '2026-09-08', 'Diproses'),
(9, 9, 9, '2026-09-09', 'Menunggu'),
(10, 10, 10, '2026-09-10', 'Selesai'),
(11, 11, 11, '2026-09-11', 'Selesai'),
(12, 12, 12, '2026-09-12', 'Diproses'),
(13, 13, 13, '2026-09-13', 'Menunggu'),
(14, 14, 14, '2026-09-14', 'Selesai'),
(15, 15, 15, '2026-09-15', 'Selesai');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tipe_koleksi`
--

CREATE TABLE `tipe_koleksi` (
  `id` int NOT NULL,
  `nama_tipe` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tipe_koleksi`
--

INSERT INTO `tipe_koleksi` (`id`, `nama_tipe`) VALUES
(1, 'Skripsi'),
(2, 'Tugas Akhir'),
(3, 'Karya Tulis Ilmiah'),
(4, 'Jurnal Penelitian'),
(5, 'Laporan Penelitian'),
(6, 'Thesis'),
(7, 'Disertasi'),
(8, 'Novel'),
(9, 'Komik');

-- --------------------------------------------------------

--
-- Table structure for table `tugas_akhir`
--

CREATE TABLE `tugas_akhir` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `judul` varchar(250) NOT NULL,
  `pembimbing` varchar(150) DEFAULT NULL,
  `file_tugas` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tugas_akhir`
--

INSERT INTO `tugas_akhir` (`id`, `user_id`, `judul`, `pembimbing`, `file_tugas`, `status`) VALUES
(1, 1, 'Analisis Sistem Informasi Perpustakaan Berbasis Web Menggunakan Laravel', NULL, 'repository/analisis_sistem_informasi_perpustakaan.pdf', 'Disetujui'),
(2, 2, 'Perancangan Sistem Informasi Akademik Berbasis Database MySQL', NULL, 'repository/perancangan_sistem_informasi_akademik.pdf', 'Disetujui'),
(3, 3, 'Pengaruh Media Sosial terhadap Minat Belajar Mahasiswa', NULL, 'repository/pengaruh_media_sosial_minat_belajar.pdf', 'Disetujui'),
(4, 4, 'Implementasi Metode Waterfall pada Pengembangan Aplikasi Berbasis Web', NULL, 'repository/implementasi_metode_waterfall.pdf', 'Menunggu'),
(5, 5, 'Analisis Kepuasan Mahasiswa terhadap Pelayanan Akademik', NULL, 'repository/analisis_kepuasan_mahasiswa.pdf', 'Disetujui'),
(6, 6, 'Penerapan Data Mining untuk Prediksi Kelulusan Mahasiswa', NULL, 'repository/penerapan_data_mining_kelulusan.pdf', 'Menunggu'),
(7, 7, 'Rancang Bangun Sistem Informasi Peminjaman Buku Perpustakaan', NULL, 'repository/rancang_bangun_sistem_peminjaman.pdf', 'Disetujui'),
(8, 8, 'Pengembangan Aplikasi Absensi Mahasiswa Berbasis QR Code', NULL, 'repository/aplikasi_absensi_qrcode.pdf', 'Disetujui'),
(9, 9, 'Sistem Informasi Repository Karya Ilmiah Mahasiswa Berbasis Web', NULL, 'repository/sistem_repository_karya_ilmiah.pdf', 'Menunggu'),
(10, 10, 'Analisis Keamanan Data pada Sistem Informasi Akademik', NULL, 'repository/keamanan_data_sistem_akademik.pdf', 'Disetujui'),
(11, 11, 'Pengembangan Perpustakaan Digital untuk Meningkatkan Akses Informasi', NULL, 'repository/pengembangan_perpustakaan_digital.pdf', 'Disetujui'),
(12, 12, 'Pemanfaatan Teknologi Informasi dalam Pengelolaan Perpustakaan', NULL, 'repository/pemanfaatan_teknologi_perpustakaan.pdf', 'Menunggu'),
(13, 13, 'Sistem Pendukung Keputusan Pemilihan Buku Referensi', NULL, 'repository/spk_pemilihan_buku.pdf', 'Disetujui'),
(14, 14, 'Analisis Efektivitas Layanan Perpustakaan Digital', NULL, 'repository/efektivitas_layanan_digital.pdf', 'Disetujui'),
(15, 15, 'Perancangan Aplikasi Mobile Library Berbasis Android', NULL, 'repository/aplikasi_mobile_library.pdf', 'Menunggu');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `jenis_user_id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nim_nip` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `jenis_user_id`, `nama`, `nim_nip`, `email`, `password`, `remember_token`) VALUES
(1, 2, 'Miftha Artika Yahdini', '2025573010049', 'mifthaartikayahdini@gmail.com', '$2y$12$0xVYkQ2i3g2D924fN0EhYO3wRDwI6Os/EUkNGMF6hM2OvqdIH95Sy', NULL),
(2, 2, 'Ahmad Fauzan', '2025573010050', 'ahmad.fauzan@gmail.com', '$2y$12$OhBeENL4wwJJUfWot/8nGeTocrCDIB7DZmp1Sr5LBvd7Hq9MlVal2', NULL),
(3, 2, 'Siti Rahmawati', '2025573010051', 'siti.rahmawati@gmail.com', '$2y$12$/AywVp9plpCZhhfbdR6D1uek0WAuMnCEH0X.m.qP/U.pN9TgRNyKe', NULL),
(4, 2, 'Rizky Pratama', '2025573010052', 'rizky.pratama@gmail.com', '$2y$12$xaAXLxkLKQTtkq9VOEU22OmWIpDvmZwR0.78lJflCEpjEsY.SyiMW', NULL),
(5, 2, 'Nabila Putri', '2025573010053', 'nabila.putri@gmail.com', '$2y$12$iQYgtC/1QJyze6vzsRaVm.BCbqccQZlwWFLxZaPs8FmhQ.LKrIDQO', NULL),
(6, 2, 'Dimas Saputra', '2025573010054', 'dimas.saputra@gmail.com', '$2y$12$EM3c.TtDoktBEvjRTA98ZOl/ESqPEiF6aEG1t.pLz/qUWUmo5Q7b.', NULL),
(7, 2, 'Aulia Ramadhan', '2025573010055', 'aulia.ramadhan@gmail.com', '$2y$12$j64CLKLyBvvzkEw7nLelQunzqhBUkuHdsGiBiYv9IiWNCIoK2HcaC', NULL),
(8, 2, 'Fajar Maulana', '2025573010056', 'fajar.maulana@gmail.com', '$2y$12$DnnJj8QZErkYHFCh/LdzGOjus9KhcJLxvkY6p/OOi7aZYXGRoyyqm', NULL),
(9, 2, 'Intan Permata', '2025573010057', 'intan.permata@gmail.com', '$2y$12$g0MT2Vcb06h153mVWmTKeu8y8IsWXdoIUMXb8O1OeG7C96dkudHVa', NULL),
(10, 2, 'Bagas Setiawan', '2025573010058', 'bagas.setiawan@gmail.com', '$2y$12$sTjEkjIMmtJ7.XBPpD5fN.9ZtGqKs5MxOE8SqKbJLN5QVfw3/MLse', NULL),
(11, 2, 'Dewi Lestari', '2025573010059', 'dewi.lestari@gmail.com', '$2y$12$HRR/hp2Sb1NmkpRqZkzr..wcgY6kMEuNmIkBUHwL91AOTVrHm6ZAO', NULL),
(12, 2, 'Rafi Akbar', '2025573010060', 'rafi.akbar@gmail.com', '$2y$12$512h36Xo35/D4GkPFQjOpeWnMe0HcXI6IudgpDmz/LeQuWOovMauq', NULL),
(13, 2, 'Putri Amelia', '2025573010061', 'putri.amelia@gmail.com', '$2y$12$gObg9sE1XmrJZDzJ3p/Fx.DvgyFGopXnllBgnzLjmu.gdnr/D4C6C', NULL),
(14, 2, 'Yoga Prasetyo', '2025573010062', 'yoga.prasetyo@gmail.com', '$2y$12$Cb1jQQ8SjxMFvPzJlsR3iu2BvICJ8.FqqDJx3N8gvpOB5i2ktUtGW', NULL),
(15, 2, 'Nanda Wijaya', '2025573010063', 'nanda.wijaya@gmail.com', '$2y$12$o.phK6OcvkvB.RAtMp3SqelHHBf6NLoEqIFqYyr30mkJIYsyfG1lG', NULL),
(32, 1, 'Muhammad Rifai', '1989345632190002', 'admin.pnl@ac.id', '$2y$12$/OwY3SYt4m3ut.oZ3unC2.snmOrMFj8jZI/VJFLaJeOVegu6P7wW2', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anggota_id` (`user_id`);

--
-- Indexes for table `artikel`
--
ALTER TABLE `artikel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anggota_id` (`user_id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`),
  ADD KEY `lokasi_id` (`lokasi_id`),
  ADD KEY `tipe_koleksi_id` (`tipe_koleksi_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `denda`
--
ALTER TABLE `denda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peminjaman_id` (`peminjaman_id`),
  ADD KEY `detail_peminjaman_id` (`detail_peminjaman_id`);

--
-- Indexes for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peminjaman_id` (`peminjaman_id`),
  ADD KEY `buku_id` (`buku_id`);

--
-- Indexes for table `ejurnal`
--
ALTER TABLE `ejurnal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_peserta`
--
ALTER TABLE `event_peserta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anggota_id` (`user_id`),
  ADD KEY `ty` (`events_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anggota_id` (`user_id`);

--
-- Indexes for table `jenis_user`
--
ALTER TABLE `jenis_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lokasi`
--
ALTER TABLE `lokasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anggota_id` (`user_id`);

--
-- Indexes for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anggota_id` (`user_id`),
  ADD KEY `buku_id` (`buku_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tipe_koleksi`
--
ALTER TABLE `tipe_koleksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tugas_akhir`
--
ALTER TABLE `tugas_akhir`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anggota_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nim_nip` (`nim_nip`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `jenis_anggota_id` (`jenis_user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `artikel`
--
ALTER TABLE `artikel`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `denda`
--
ALTER TABLE `denda`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `ejurnal`
--
ALTER TABLE `ejurnal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `event_peserta`
--
ALTER TABLE `event_peserta`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `lokasi`
--
ALTER TABLE `lokasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `reservasi`
--
ALTER TABLE `reservasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tipe_koleksi`
--
ALTER TABLE `tipe_koleksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tugas_akhir`
--
ALTER TABLE `tugas_akhir`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `artikel`
--
ALTER TABLE `artikel`
  ADD CONSTRAINT `artikel_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `artikel_ibfk_2` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `buku_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `buku_ibfk_2` FOREIGN KEY (`lokasi_id`) REFERENCES `lokasi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `buku_ibfk_3` FOREIGN KEY (`tipe_koleksi_id`) REFERENCES `tipe_koleksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `denda`
--
ALTER TABLE `denda`
  ADD CONSTRAINT `denda_ibfk_1` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `denda_ibfk_2` FOREIGN KEY (`detail_peminjaman_id`) REFERENCES `detail_peminjaman` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD CONSTRAINT `detail_peminjaman_ibfk_1` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_peminjaman_ibfk_2` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ejurnal`
--
ALTER TABLE `ejurnal`
  ADD CONSTRAINT `ejurnal_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_peserta`
--
ALTER TABLE `event_peserta`
  ADD CONSTRAINT `event_peserta_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `event_peserta_ibfk_2` FOREIGN KEY (`events_id`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD CONSTRAINT `reservasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservasi_ibfk_2` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tugas_akhir`
--
ALTER TABLE `tugas_akhir`
  ADD CONSTRAINT `tugas_akhir_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`jenis_user_id`) REFERENCES `jenis_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;



-- ===== Sinkronisasi dengan kode aplikasi (perbaikan_sinkron.sql) =====

-- 1. peminjaman: petugas yang memverifikasi / menerima pengembalian
SET @s = IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'peminjaman' AND COLUMN_NAME = 'petugas_id') = 0,
  'ALTER TABLE `peminjaman` ADD COLUMN `petugas_id` INT NULL AFTER `user_id`', 'DO 0');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;
SET @s = IF((SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'peminjaman' AND CONSTRAINT_NAME = 'peminjaman_petugas_fk') = 0,
  'ALTER TABLE `peminjaman` ADD KEY `petugas_id` (`petugas_id`), ADD CONSTRAINT `peminjaman_petugas_fk` FOREIGN KEY (`petugas_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE', 'DO 0');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

-- 2. tugas_akhir: reviewer dan catatan review
SET @s = IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tugas_akhir' AND COLUMN_NAME = 'reviewer_id') = 0,
  'ALTER TABLE `tugas_akhir` ADD COLUMN `reviewer_id` INT NULL', 'DO 0');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;
SET @s = IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tugas_akhir' AND COLUMN_NAME = 'catatan_reviewer') = 0,
  'ALTER TABLE `tugas_akhir` ADD COLUMN `catatan_reviewer` TEXT NULL', 'DO 0');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;
SET @s = IF((SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tugas_akhir' AND CONSTRAINT_NAME = 'tugas_akhir_reviewer_fk') = 0,
  'ALTER TABLE `tugas_akhir` ADD KEY `reviewer_id` (`reviewer_id`), ADD CONSTRAINT `tugas_akhir_reviewer_fk` FOREIGN KEY (`reviewer_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE', 'DO 0');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

-- 3. feedback: kolom balasan dulu berisi tanggal -> pindah ke kolom tanggal
SET @s = IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'feedback' AND COLUMN_NAME = 'tanggal') = 0,
  'ALTER TABLE `feedback` ADD COLUMN `tanggal` DATE NULL', 'DO 0');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;
UPDATE `feedback` SET `tanggal` = `balasan` WHERE `tanggal` IS NULL AND `balasan` REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$';
UPDATE `feedback` SET `balasan` = NULL WHERE `balasan` REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$';
UPDATE `feedback` SET `jenis` = 'saran' WHERE `jenis` IS NULL;
UPDATE `feedback` SET `status` = IF(`balasan` IS NULL OR `balasan` = '', 'Belum Dibalas', 'Sudah Dibalas');

-- 4. event & event_peserta: isi kolom yang masih kosong
UPDATE `event` SET `lokasi` = 'Perpustakaan PNL' WHERE `lokasi` = '';
UPDATE `event` SET `deskripsi` = CONCAT('Kegiatan perpustakaan: ', `judul`, '. Terbuka untuk mahasiswa dan dosen PNL.') WHERE `deskripsi` IS NULL;
UPDATE `event_peserta` ep JOIN `event` e ON e.`id` = ep.`events_id` SET ep.`tanggal_daftar` = e.`tanggal_mulai` WHERE ep.`tanggal_daftar` IS NULL;

-- 5. Tabel cache (dipakai pembatas percobaan login bila CACHE_STORE=database)
CREATE TABLE IF NOT EXISTS `cache` (`key` VARCHAR(255) NOT NULL, `value` MEDIUMTEXT NOT NULL, `expiration` INT NOT NULL, PRIMARY KEY (`key`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS `cache_locks` (`key` VARCHAR(255) NOT NULL, `owner` VARCHAR(255) NOT NULL, `expiration` INT NOT NULL, PRIMARY KEY (`key`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Akun contoh untuk menguji role Petugas dan Dosen (hanya dibuat jika belum ada).
--    Password: petugas123 / dosen123  -> GANTI sebelum dipakai sungguhan / dipublikasikan.
INSERT INTO `user` (`jenis_user_id`, `nama`, `nim_nip`, `email`, `password`)
SELECT j.`id`, 'Petugas Perpustakaan', '198800000000000001', 'petugas.pnl@ac.id', '$2y$12$UyPLUxTq5pknY8sgMbYSx.Il2v.Sc1xQhJihkpyly33zrOpP2vScC'
FROM `jenis_user` j WHERE j.`nama_role` = 'Petugas'
  AND NOT EXISTS (SELECT 1 FROM `user` WHERE `email` = 'petugas.pnl@ac.id');
INSERT INTO `user` (`jenis_user_id`, `nama`, `nim_nip`, `email`, `password`)
SELECT j.`id`, 'Dosen Contoh', '198500000000000002', 'dosen.pnl@ac.id', '$2y$12$UgmUW8Bb35a0gy.3qavieOhiOxBsS6uHDJS2udW1aiXGHO4zkVwnq'
FROM `jenis_user` j WHERE j.`nama_role` = 'Dosen'
  AND NOT EXISTS (SELECT 1 FROM `user` WHERE `email` = 'dosen.pnl@ac.id');

-- Nilai status yang dipakai aplikasi:
--   peminjaman : Menunggu -> Dipinjam -> Dikembalikan (atau Ditolak); terlambat = Dipinjam lewat jatuh tempo
--   reservasi  : Menunggu, Diproses, Selesai, Dibatalkan
--   tugas_akhir: Menunggu, Disetujui, Ditolak
--   feedback   : Belum Dibalas, Sudah Dibalas

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
