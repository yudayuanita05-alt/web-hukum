-- ==============================================================================
-- SISTEM INFORMASI LAYANAN DAN KONSULTASI HUKUM BERBASIS WEBSITE
-- PADA KANTOR AHMAD HANDOKO LAW OFFICE BANDAR LAMPUNG
-- Skripsi: Firhan Danuarta (NPM 22430035) - Universitas Muhammadiyah Metro 2026
-- ==============================================================================
-- SKEMA BASIS DATA YANG SUDAH DISEMPURNAKAN (MEMENUHI 1NF - 3NF SECARA PENUH)
-- Aturan Primary Key: Wajib id_<nama_tabel>
-- ==============================================================================

CREATE DATABASE IF NOT EXISTS `db_hukum` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_hukum`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `web_hukum_chat`;
DROP TABLE IF EXISTS `web_hukum_pembayaran`;
DROP TABLE IF EXISTS `web_hukum_kontrak_kesepakatan`;
DROP TABLE IF EXISTS `web_hukum_formulir_konsultasi`;
DROP TABLE IF EXISTS `web_hukum_user`;
DROP TABLE IF EXISTS `web_hukum_klasterisasi`;
DROP TABLE IF EXISTS `web_hukum_role`;
SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------------------------------------
-- 1. TABEL MASTER: web_hukum_role
-- PK: id_role (Menghilangkan anomali hardcoded role)
-- ------------------------------------------------------------------------------
CREATE TABLE `web_hukum_role` (
  `id_role` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_role` VARCHAR(50) NOT NULL,
  `deskripsi` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 2. TABEL MASTER: web_hukum_klasterisasi
-- PK: id_klasterisasi (Menghilangkan anomali hardcoded klaster perkara)
-- ------------------------------------------------------------------------------
CREATE TABLE `web_hukum_klasterisasi` (
  `id_klasterisasi` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_klasterisasi` VARCHAR(100) NOT NULL,
  `keterangan` TEXT DEFAULT NULL,
  PRIMARY KEY (`id_klasterisasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 3. TABEL: web_hukum_user
-- PK: id_user | FK: id_role -> web_hukum_role(id_role)
-- ------------------------------------------------------------------------------
CREATE TABLE `web_hukum_user` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `id_role` INT(11) NOT NULL COMMENT 'FK ke web_hukum_role(id_role)',
  `name` VARCHAR(128) NOT NULL,
  `username` VARCHAR(128) NOT NULL,
  `image` VARCHAR(128) NOT NULL DEFAULT 'default.jpg',
  `password` VARCHAR(256) NOT NULL,
  `is_active` INT(1) NOT NULL DEFAULT 1 COMMENT '0: Nonaktif, 1: Aktif',
  `date_created` INT(11) NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `idx_username` (`username`),
  KEY `fk_user_role` (`id_role`),
  CONSTRAINT `fk_user_role` FOREIGN KEY (`id_role`) REFERENCES `web_hukum_role` (`id_role`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 4. TABEL: web_hukum_formulir_konsultasi
-- PK: id_formulir
-- FK: id_user -> web_hukum_user(id_user)
-- FK: id_user_pengacara -> web_hukum_user(id_user) [SEBELUMNYA TIDAK ADA RELASI]
-- FK: id_klasterisasi -> web_hukum_klasterisasi(id_klasterisasi)
-- ------------------------------------------------------------------------------
CREATE TABLE `web_hukum_formulir_konsultasi` (
  `id_formulir` INT(11) NOT NULL AUTO_INCREMENT,
  `id_user` INT(11) NOT NULL COMMENT 'FK ke pemohon/klien',
  `id_user_pengacara` INT(11) DEFAULT NULL COMMENT 'FK ke pengacara penanggung jawab',
  `id_klasterisasi` INT(11) DEFAULT NULL COMMENT 'FK ke master klaster kasus',
  `no_permohonan` VARCHAR(100) NOT NULL,
  `nama` VARCHAR(255) NOT NULL,
  `tempat_lahir` VARCHAR(100) DEFAULT NULL,
  `tgl_lahir` DATE DEFAULT NULL,
  `jenis_kelamin` ENUM('Laki-laki', 'Perempuan') DEFAULT NULL,
  `agama` VARCHAR(50) DEFAULT NULL,
  `alamat` TEXT DEFAULT NULL,
  `no_hp` VARCHAR(30) DEFAULT NULL,
  `pekerjaan` VARCHAR(100) DEFAULT NULL,
  `waktu_pelaksanaan` DATE DEFAULT NULL,
  `uraian_singkat_perkara` TEXT DEFAULT NULL,
  `ttd_pemohon` VARCHAR(255) DEFAULT NULL COMMENT 'File PNG tanda tangan klien',
  `ttd_konsultan` VARCHAR(255) DEFAULT NULL COMMENT 'File PNG tanda tangan advokat',
  `bukti_awal` VARCHAR(255) DEFAULT NULL COMMENT 'Dokumen bukti perkara awal',
  `status_verifikasi` INT(1) NOT NULL DEFAULT 0 COMMENT '0: Menunggu, 1: Disetujui, 2: Ditolak',
  `status_kasus_pengacara` INT(1) NOT NULL DEFAULT 0 COMMENT '0: Masuk, 1: Dalam Progres, 2: Selesai',
  `status_jadwal_temu` INT(1) NOT NULL DEFAULT 0 COMMENT '0: Belum Ada, 1: Menunggu, 2: Terjadwal, 3: Selesai',
  `tgl_waktu_jadwal_temu` DATETIME DEFAULT NULL,
  `catatan_jadwal_temu` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_formulir`),
  UNIQUE KEY `idx_no_permohonan` (`no_permohonan`),
  KEY `fk_formulir_user` (`id_user`),
  KEY `fk_formulir_pengacara` (`id_user_pengacara`),
  KEY `fk_formulir_klasterisasi` (`id_klasterisasi`),
  CONSTRAINT `fk_formulir_user` FOREIGN KEY (`id_user`) REFERENCES `web_hukum_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_formulir_pengacara` FOREIGN KEY (`id_user_pengacara`) REFERENCES `web_hukum_user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_formulir_klasterisasi` FOREIGN KEY (`id_klasterisasi`) REFERENCES `web_hukum_klasterisasi` (`id_klasterisasi`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 5. TABEL: web_hukum_kontrak_kesepakatan
-- PK: id_kontrak | FK: id_formulir -> web_hukum_formulir_konsultasi(id_formulir)
-- ------------------------------------------------------------------------------
CREATE TABLE `web_hukum_kontrak_kesepakatan` (
  `id_kontrak` INT(11) NOT NULL AUTO_INCREMENT,
  `id_formulir` INT(11) NOT NULL COMMENT 'FK ke formulir konsultasi',
  `keterangan` VARCHAR(255) DEFAULT NULL,
  `berkas_kontrak` VARCHAR(255) DEFAULT NULL COMMENT 'Path berkas kontrak PDF',
  `status_sidang` INT(1) NOT NULL DEFAULT 0 COMMENT '0: Non-Litigasi, 1: Gugatan, 2: Pembuktian, 3: Putusan, 4: Inkracht',
  `created_at` DATE NOT NULL,
  PRIMARY KEY (`id_kontrak`),
  KEY `fk_kontrak_formulir` (`id_formulir`),
  CONSTRAINT `fk_kontrak_formulir` FOREIGN KEY (`id_formulir`) REFERENCES `web_hukum_formulir_konsultasi` (`id_formulir`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 6. TABEL: web_hukum_pembayaran
-- PK: id_pembayaran | FK: id_formulir -> web_hukum_formulir_konsultasi(id_formulir)
-- [INTEGRASI PAYMENT GATEWAY]: Mendukung Midtrans / QRIS / Virtual Account / E-Wallet
-- Callback webhook otomatis mengubah status menjadi Settlement/Lunas
-- ------------------------------------------------------------------------------
CREATE TABLE `web_hukum_pembayaran` (
  `id_pembayaran` INT(11) NOT NULL AUTO_INCREMENT,
  `id_formulir` INT(11) NOT NULL COMMENT 'FK ke formulir perkara',
  `order_id` VARCHAR(100) NOT NULL COMMENT 'Kode unik transaksi gateway, misal: ORDER-HKM-YYYYMMDD-XXXX',
  `jumlah_tagihan` DECIMAL(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Nominal angka presisi biaya penanganan',
  `metode_pembayaran` VARCHAR(50) DEFAULT NULL COMMENT 'qris, bca_va, bni_va, bri_va, gopay, dll.',
  `transaction_id` VARCHAR(255) DEFAULT NULL COMMENT 'ID transaksi resmi dari payment gateway',
  `snap_token` VARCHAR(255) DEFAULT NULL COMMENT 'Token transaksi Midtrans Snap',
  `payment_url` VARCHAR(255) DEFAULT NULL COMMENT 'URL redirect pembayaran online',
  `status` INT(1) NOT NULL DEFAULT 0 COMMENT '0: Pending/Belum Bayar, 1: Settlement/Lunas, 2: Expired, 3: Cancel/Ditolak',
  `status_transaksi` VARCHAR(50) NOT NULL DEFAULT 'pending' COMMENT 'Status payment gateway: pending, settlement, expire, cancel, deny',
  `bukti_bayar` VARCHAR(255) DEFAULT NULL COMMENT 'Opsional: URL / path struk bukti pembayaran',
  `keterangan` TEXT DEFAULT NULL,
  `waktu_transaksi` DATETIME DEFAULT NULL COMMENT 'Timestamp pelunasan pembayaran dari webhook',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pembayaran`),
  UNIQUE KEY `idx_order_id` (`order_id`),
  KEY `fk_bayar_formulir` (`id_formulir`),
  CONSTRAINT `fk_bayar_formulir` FOREIGN KEY (`id_formulir`) REFERENCES `web_hukum_formulir_konsultasi` (`id_formulir`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 7. TABEL: web_hukum_chat
-- PK: id_chat | FK: id_pengirim & id_penerima -> web_hukum_user(id_user)
-- ------------------------------------------------------------------------------
CREATE TABLE `web_hukum_chat` (
  `id_chat` INT(11) NOT NULL AUTO_INCREMENT,
  `id_pengirim` INT(11) NOT NULL,
  `id_penerima` INT(11) NOT NULL,
  `id_formulir` INT(11) DEFAULT NULL,
  `pesan` TEXT NOT NULL,
  `is_read` INT(1) NOT NULL DEFAULT 0 COMMENT '0: Belum Dibaca, 1: Terbaca',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_chat`),
  KEY `fk_chat_pengirim` (`id_pengirim`),
  KEY `fk_chat_penerima` (`id_penerima`),
  KEY `fk_chat_formulir` (`id_formulir`),
  CONSTRAINT `fk_chat_pengirim` FOREIGN KEY (`id_pengirim`) REFERENCES `web_hukum_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_chat_penerima` FOREIGN KEY (`id_penerima`) REFERENCES `web_hukum_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_chat_formulir` FOREIGN KEY (`id_formulir`) REFERENCES `web_hukum_formulir_konsultasi` (`id_formulir`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- DATA AWAL (SEEDING LENGKAP & RELASIONAL)
-- Password default akun: password123
-- ------------------------------------------------------------------------------

-- Master Role
INSERT INTO `web_hukum_role` (`id_role`, `nama_role`, `deskripsi`) VALUES
(1, 'Sekretaris (Admin)', 'Pengelola sistem, verifikator berkas, chat helpdesk, dan update progres perkara'),
(2, 'Pengacara / Advokat', 'Penanggung jawab perkara hukum, konsultasi, dan persidangan'),
(3, 'Klien / Pemohon', 'Pengaju konsultasi hukum, pemohon perkara, dan pembayar tagihan');

-- Master Klasterisasi Perkara (Sesuai Profil Kantor Hukum di Bab I Skripsi)
INSERT INTO `web_hukum_klasterisasi` (`id_klasterisasi`, `nama_klasterisasi`, `keterangan`) VALUES
(1, 'Tindak Pidana Korupsi (Tipikor)', 'Perkara dugaan tindak pidana korupsi dan gratifikasi'),
(2, 'Tata Usaha Negara (TUN)', 'Sengketa kebijakan pejabat/badan tata usaha negara'),
(3, 'Hukum Perdata', 'Sengketa wanprestasi, perjanjian, ganti rugi, waris, dan sengketa tanah'),
(4, 'Hukum Pidana', 'Pendampingan perkara tindak pidana umum dan khusus'),
(5, 'Perbankan & Keuangan', 'Sengketa kredit macet, jaminan hak tanggungan, dan transaksi perbankan'),
(6, 'Sengketa Pemilukada / Pilkada', 'Sengketa perselisihan hasil pemilu kepala daerah dan legislatif'),
(7, 'Perusahaan, Bisnis & Pajak', 'Hukum korporasi, legal audit, ketenagakerjaan, dan sengketa pajak');

-- User Awal
INSERT INTO `web_hukum_user` (`id_user`, `id_role`, `name`, `username`, `image`, `password`, `is_active`, `date_created`) VALUES
(1, 1, 'Sekretaris Kantor', 'admin', 'default.jpg', '$2y$10$4y9pYy9F7QeT1mXj2w3pI.0u6vH.UeYcI5gGq6m7J2rK8sL9t1n0u', 1, 1762711200),
(2, 2, 'Yopi Hendro, S.H., M.H.', 'pengacara1', 'default.jpg', '$2y$10$4y9pYy9F7QeT1mXj2w3pI.0u6vH.UeYcI5gGq6m7J2rK8sL9t1n0u', 1, 1762711200),
(3, 3, 'Firhan Danuarta', 'klien1', 'default.jpg', '$2y$10$4y9pYy9F7QeT1mXj2w3pI.0u6vH.UeYcI5gGq6m7J2rK8sL9t1n0u', 1, 1762711200);
