-- =====================================================
-- Sinkronisasi database dengan kode aplikasi (IDEMPOTEN)
-- Aman dijalankan berulang kali pada database web_perpustakaan.
-- Sudah otomatis ikut di dalam web_perpustakaan.sql; file ini
-- hanya perlu dijalankan terpisah jika kamu sudah punya database lama.
-- =====================================================

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
