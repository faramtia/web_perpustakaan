-- =====================================================
-- Sinkronisasi database dengan kode aplikasi
-- Jalankan SEKALI pada database web_perpustakaan
-- (setelah perbaikan_auth.sql dari paket sebelumnya)
-- =====================================================

-- 1. peminjaman: mencatat petugas yang memverifikasi / menerima pengembalian
ALTER TABLE `peminjaman`
  ADD COLUMN `petugas_id` INT NULL AFTER `user_id`,
  ADD KEY `petugas_id` (`petugas_id`),
  ADD CONSTRAINT `peminjaman_petugas_fk` FOREIGN KEY (`petugas_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- 2. tugas_akhir: reviewer dan catatan review
ALTER TABLE `tugas_akhir`
  ADD COLUMN `reviewer_id` INT NULL,
  ADD COLUMN `catatan_reviewer` TEXT NULL,
  ADD KEY `reviewer_id` (`reviewer_id`),
  ADD CONSTRAINT `tugas_akhir_reviewer_fk` FOREIGN KEY (`reviewer_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- 3. feedback: kolom balasan sebelumnya berisi tanggal. Pindahkan ke kolom tanggal baru.
ALTER TABLE `feedback` ADD COLUMN `tanggal` DATE NULL;

UPDATE `feedback` SET `tanggal` = `balasan`
WHERE `balasan` REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$';

UPDATE `feedback` SET `balasan` = NULL
WHERE `balasan` REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$';

UPDATE `feedback` SET `jenis` = 'saran' WHERE `jenis` IS NULL;

-- Status disamakan: 'Belum Dibalas' / 'Sudah Dibalas' (sebelumnya campur 'Belum Dibaca' / 'Sudah Dibaca')
UPDATE `feedback`
SET `status` = IF(`balasan` IS NULL OR `balasan` = '', 'Belum Dibalas', 'Sudah Dibalas');

-- 4. Tabel cache dipakai fitur pembatas percobaan login (CACHE_STORE=database).
--    Tidak wajib kalau di .env kamu pakai CACHE_STORE=file.
CREATE TABLE IF NOT EXISTS `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Nilai status yang dipakai aplikasi sekarang:
--   peminjaman : Menunggu -> Dipinjam -> Dikembalikan (atau Ditolak)
--                (terlambat = Dipinjam yang lewat tanggal_jatuh_tempo; denda dicatat di tabel denda)
--   reservasi  : Menunggu, Diproses, Selesai, Dibatalkan
--   tugas_akhir: Menunggu, Disetujui, Ditolak
--   feedback   : Belum Dibalas, Sudah Dibalas
--   event_peserta: Terdaftar
