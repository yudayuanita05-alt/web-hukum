-- ==============================================================================
-- SKRIP IMPORT BERSIH UNTUK TiDB CLOUD (100% KOMPATIBEL TANPA ERROR AUTO_INCREMENT)
-- Kantor Hukum Ahmad Handoko Law Office
-- ==============================================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `web_hukum_chat`;
DROP TABLE IF EXISTS `web_hukum_pembayaran`;
DROP TABLE IF EXISTS `web_hukum_kontrak_kesepakatan`;
DROP TABLE IF EXISTS `web_hukum_formulir_konsultasi`;
DROP TABLE IF EXISTS `web_hukum_user`;
DROP TABLE IF EXISTS `web_hukum_klasterisasi`;
DROP TABLE IF EXISTS `web_hukum_role`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `cache`;

-- 1. TABEL: web_hukum_role
CREATE TABLE `web_hukum_role` (
  `id_role` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_role` VARCHAR(50) NOT NULL,
  `deskripsi` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. TABEL: web_hukum_klasterisasi
CREATE TABLE `web_hukum_klasterisasi` (
  `id_klasterisasi` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_klasterisasi` VARCHAR(100) NOT NULL,
  `keterangan` TEXT DEFAULT NULL,
  PRIMARY KEY (`id_klasterisasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. TABEL: web_hukum_user
CREATE TABLE `web_hukum_user` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `id_role` INT(11) NOT NULL,
  `name` VARCHAR(128) NOT NULL,
  `username` VARCHAR(128) NOT NULL,
  `image` VARCHAR(128) NOT NULL DEFAULT 'default.jpg',
  `password` VARCHAR(256) NOT NULL,
  `is_active` INT(1) NOT NULL DEFAULT 1,
  `date_created` INT(11) NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `idx_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. TABEL: web_hukum_formulir_konsultasi
CREATE TABLE `web_hukum_formulir_konsultasi` (
  `id_formulir` INT(11) NOT NULL AUTO_INCREMENT,
  `id_user` INT(11) NOT NULL,
  `id_user_pengacara` INT(11) DEFAULT NULL,
  `id_klasterisasi` INT(11) DEFAULT NULL,
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
  `ttd_pemohon` VARCHAR(255) DEFAULT NULL,
  `ttd_konsultan` VARCHAR(255) DEFAULT NULL,
  `bukti_awal` VARCHAR(255) DEFAULT NULL,
  `status_verifikasi` INT(1) NOT NULL DEFAULT 0,
  `status_kasus_pengacara` INT(1) NOT NULL DEFAULT 0,
  `status_jadwal_temu` INT(1) NOT NULL DEFAULT 0,
  `tgl_waktu_jadwal_temu` DATETIME DEFAULT NULL,
  `catatan_jadwal_temu` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_formulir`),
  UNIQUE KEY `idx_no_permohonan` (`no_permohonan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. TABEL: web_hukum_kontrak_kesepakatan
CREATE TABLE `web_hukum_kontrak_kesepakatan` (
  `id_kontrak` INT(11) NOT NULL AUTO_INCREMENT,
  `id_formulir` INT(11) NOT NULL,
  `keterangan` VARCHAR(255) DEFAULT NULL,
  `berkas_kontrak` VARCHAR(255) DEFAULT NULL,
  `status_sidang` INT(1) NOT NULL DEFAULT 0,
  `created_at` DATE NOT NULL,
  PRIMARY KEY (`id_kontrak`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. TABEL: web_hukum_pembayaran
CREATE TABLE `web_hukum_pembayaran` (
  `id_pembayaran` INT(11) NOT NULL AUTO_INCREMENT,
  `id_formulir` INT(11) NOT NULL,
  `order_id` VARCHAR(100) NOT NULL,
  `jumlah_tagihan` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `metode_pembayaran` VARCHAR(50) DEFAULT NULL,
  `transaction_id` VARCHAR(255) DEFAULT NULL,
  `snap_token` VARCHAR(255) DEFAULT NULL,
  `payment_url` VARCHAR(255) DEFAULT NULL,
  `status` INT(1) NOT NULL DEFAULT 0,
  `status_transaksi` VARCHAR(50) NOT NULL DEFAULT 'pending',
  `bukti_bayar` VARCHAR(255) DEFAULT NULL,
  `keterangan` TEXT DEFAULT NULL,
  `waktu_transaksi` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id_pembayaran`),
  UNIQUE KEY `idx_order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. TABEL: web_hukum_chat
CREATE TABLE `web_hukum_chat` (
  `id_chat` INT(11) NOT NULL AUTO_INCREMENT,
  `id_pengirim` INT(11) NOT NULL,
  `id_penerima` INT(11) NOT NULL,
  `id_formulir` INT(11) DEFAULT NULL,
  `pesan` TEXT NOT NULL,
  `is_read` INT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_chat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. TABEL PELENGKAP LARAVEL (sessions & cache)
CREATE TABLE `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` TEXT DEFAULT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` INT(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================================
-- DATA SEEDING (Password default semua akun: password123)
-- ==============================================================================

-- Roles
INSERT INTO `web_hukum_role` (`id_role`, `nama_role`, `deskripsi`) VALUES
(1, 'Sekretaris (Admin)', 'Pengelola sistem, verifikator berkas, chat helpdesk, dan update progres perkara'),
(2, 'Pengacara / Advokat', 'Penanggung jawab perkara hukum, konsultasi, dan persidangan'),
(3, 'Klien / Pemohon', 'Pengaju konsultasi hukum, pemohon perkara, dan pembayar tagihan'),
(4, 'Pimpinan / Managing Partner', 'Pimpinan kantor hukum Handoko & Partners');

-- Klasterisasi Perkara
INSERT INTO `web_hukum_klasterisasi` (`id_klasterisasi`, `nama_klasterisasi`, `keterangan`) VALUES
(1, 'Tindak Pidana Korupsi (Tipikor)', 'Perkara dugaan tindak pidana korupsi dan gratifikasi'),
(2, 'Tata Usaha Negara (TUN)', 'Sengketa kebijakan pejabat/badan tata usaha negara'),
(3, 'Hukum Perdata', 'Sengketa wanprestasi, perjanjian, ganti rugi, waris, dan sengketa tanah'),
(4, 'Hukum Pidana', 'Pendampingan perkara tindak pidana umum dan khusus'),
(5, 'Perbankan & Keuangan', 'Sengketa kredit macet, jaminan hak tanggungan, dan transaksi perbankan'),
(6, 'Sengketa Pemilukada / Pilkada', 'Sengketa perselisihan hasil pemilu kepala daerah dan legislatif'),
(7, 'Perusahaan, Bisnis & Pajak', 'Hukum korporasi, legal audit, ketenagakerjaan, dan sengketa pajak');

-- Data User (Hash bcrypt untuk 'password123')
INSERT INTO `web_hukum_user` (`id_user`, `id_role`, `name`, `username`, `image`, `password`, `is_active`, `date_created`) VALUES
(1, 1, 'Sekretaris Kantor', 'admin', 'default.jpg', '$2y$12$RjVjS62hBwJkXkZvZ5kEPeUfU6oOvyFhUfx4v9n0w5v0yUaWz6Gey', 1, 1762711200),
(2, 4, 'Ahmad Handoko, S.H., M.H.', 'pimpinan', 'default.jpg', '$2y$12$RjVjS62hBwJkXkZvZ5kEPeUfU6oOvyFhUfx4v9n0w5v0yUaWz6Gey', 1, 1762711200),
(3, 2, 'Yopi Hendro, S.H., M.H.', 'pengacara1', 'default.jpg', '$2y$12$RjVjS62hBwJkXkZvZ5kEPeUfU6oOvyFhUfx4v9n0w5v0yUaWz6Gey', 1, 1762711200),
(4, 2, 'Ahmad Handoko, S.H., M.H.', 'ahmad_handoko', 'default.jpg', '$2y$12$RjVjS62hBwJkXkZvZ5kEPeUfU6oOvyFhUfx4v9n0w5v0yUaWz6Gey', 1, 1762711200),
(5, 2, 'M. Reza Pahlevi, S.H., M.H.', 'reza_pahlevi', 'default.jpg', '$2y$12$RjVjS62hBwJkXkZvZ5kEPeUfU6oOvyFhUfx4v9n0w5v0yUaWz6Gey', 1, 1762711200),
(6, 2, 'Dian Safitri, S.H., M.Kn.', 'dian_safitri', 'default.jpg', '$2y$12$RjVjS62hBwJkXkZvZ5kEPeUfU6oOvyFhUfx4v9n0w5v0yUaWz6Gey', 1, 1762711200),
(7, 2, 'Fajar Ramadhan, S.H.', 'fajar_ramadhan', 'default.jpg', '$2y$12$RjVjS62hBwJkXkZvZ5kEPeUfU6oOvyFhUfx4v9n0w5v0yUaWz6Gey', 1, 1762711200),
(8, 3, 'Firhan Danuarta', 'klien1', 'default.jpg', '$2y$12$RjVjS62hBwJkXkZvZ5kEPeUfU6oOvyFhUfx4v9n0w5v0yUaWz6Gey', 1, 1762711200),
(9, 3, 'Budi Santoso', 'klien2', 'default.jpg', '$2y$12$RjVjS62hBwJkXkZvZ5kEPeUfU6oOvyFhUfx4v9n0w5v0yUaWz6Gey', 1, 1762711200),
(10, 3, 'Siti Nurhaliza', 'klien3', 'default.jpg', '$2y$12$RjVjS62hBwJkXkZvZ5kEPeUfU6oOvyFhUfx4v9n0w5v0yUaWz6Gey', 1, 1762711200),
(11, 3, 'Andi Wijaya', 'klien4', 'default.jpg', '$2y$12$RjVjS62hBwJkXkZvZ5kEPeUfU6oOvyFhUfx4v9n0w5v0yUaWz6Gey', 1, 1762711200),
(12, 3, 'Hendra Kurniawan', 'klien5', 'default.jpg', '$2y$12$RjVjS62hBwJkXkZvZ5kEPeUfU6oOvyFhUfx4v9n0w5v0yUaWz6Gey', 1, 1762711200);

-- Data Kasus / Formulir Konsultasi
INSERT INTO `web_hukum_formulir_konsultasi` (
  `id_formulir`, `id_user`, `id_user_pengacara`, `id_klasterisasi`, `no_permohonan`, `nama`, `tempat_lahir`, `tgl_lahir`, `jenis_kelamin`, `agama`,
  `alamat`, `no_hp`, `pekerjaan`, `waktu_pelaksanaan`, `uraian_singkat_perkara`, `ttd_pemohon`, `ttd_konsultan`, `bukti_awal`,
  `status_verifikasi`, `status_kasus_pengacara`, `status_jadwal_temu`, `tgl_waktu_jadwal_temu`, `catatan_jadwal_temu`, `created_at`
) VALUES
(1, 8, 3, 4, 'REG-HKM-20261112-0001', 'Firhan Danuarta', 'Metro', '2003-05-14', 'Laki-laki', 'Islam', 'Jl. Ki Hajar Dewantara No. 15, Metro Timur', '081234567890', 'Mahasiswa / Wiraswasta', '2026-11-20', 'Pendampingan hukum dugaan tindak pidana sengketa hak cipta perangkat lunak.', 'ttd_pemohon_3_1794465291.png', 'ttd_konsultan_2_1794465618.png', NULL, 1, 1, 2, '2026-11-20 10:00:00', 'Konsultasi Ruang Mediasi 1', '2026-11-12 10:00:00'),
(2, 9, 5, 3, 'REG-HKM-20260810-SLS01', 'Budi Santoso', 'Bandar Lampung', '1985-04-12', 'Laki-laki', 'Islam', 'Jl. Raden Intan No. 45, Bandar Lampung', '082198765432', 'Pengusaha Properti', '2026-08-15', 'Sengketa wanprestasi perjanjian jual beli tanah dan bangunan seluas 1.200 m2 di Teluk Betung.', 'ttd_pemohon_3_1794465291.png', 'ttd_konsultan_2_1794465618.png', NULL, 1, 2, 3, '2026-08-15 09:30:00', 'Selesai Sidang Putusan Pengadilan Negeri', '2026-08-10 08:30:00'),
(3, 10, 6, 3, 'REG-HKM-20260822-SLS02', 'Siti Nurhaliza', 'Metro', '1990-11-28', 'Perempuan', 'Islam', 'Jl. Ahmad Yani No. 12, Kota Metro', '085712348765', 'PNS Daerah', '2026-08-28', 'Gugatan pembagian harta bersama dan waris keluarga besar almarhum H. Mansyur.', 'ttd_pemohon_3_1794465291.png', 'ttd_konsultan_2_1794465618.png', NULL, 1, 2, 3, '2026-08-28 14:00:00', 'Mediasi Pengadilan Agama Berhasil', '2026-08-22 13:15:00'),
(4, 11, 4, 1, 'REG-HKM-20260905-SLS03', 'Andi Wijaya', 'Kalianda', '1978-02-15', 'Laki-laki', 'Kristen', 'Jl. Lintas Sumatera Km. 35, Kalianda', '081399887766', 'Direktur CV Mandiri Jaya', '2026-09-10', 'Pendampingan penyidikan tindak pidana korupsi pengadaan barang dan jasa APBD.', 'ttd_pemohon_3_1794465291.png', 'ttd_konsultan_2_1794465618.png', NULL, 1, 2, 3, '2026-09-10 10:00:00', 'Putusan Bebas PN Tipikor Tanjungkarang', '2026-09-05 09:00:00'),
(5, 12, 7, 7, 'REG-HKM-20261118-0005', 'Hendra Kurniawan', 'Bandar Lampung', '1982-07-20', 'Laki-laki', 'Islam', 'Jl. Kartini No. 88, Tanjung Karang Pusat', '081922334455', 'Manajer Operasional PT Jaya Abadi', '2026-11-22', 'Perselisihan Hubungan Industrial (PHI) pemutusan hubungan kerja sepihak 12 karyawan.', 'ttd_pemohon_3_1794465291.png', 'ttd_konsultan_2_1794465618.png', NULL, 1, 1, 2, '2026-11-22 13:30:00', 'Agenda Sidang Mediasi Dinaskertrans', '2026-11-18 11:20:00');

-- Data Kontrak
INSERT INTO `web_hukum_kontrak_kesepakatan` (`id_kontrak`, `id_formulir`, `keterangan`, `berkas_kontrak`, `status_sidang`, `created_at`) VALUES
(1, 1, 'Perjanjian Jasa Hukum Pendampingan Pidana Hak Cipta', 'kontrak_0001.pdf', 1, '2026-11-12'),
(2, 2, 'Perjanjian Retainer Litigasi Sengketa Tanah Perdata', 'kontrak_0002.pdf', 4, '2026-08-11'),
(3, 3, 'Perjanjian Jasa Hukum Non-Litigasi & Mediasi Waris', 'kontrak_0003.pdf', 4, '2026-08-23'),
(4, 4, 'Perjanjian Pembelaan Perkara Tipikor', 'kontrak_0004.pdf', 4, '2026-09-06'),
(5, 5, 'Perjanjian Pendampingan Kasus Ketenagakerjaan PHI', 'kontrak_0005.pdf', 2, '2026-11-18');

-- Data Pembayaran (Lengkap Status Settlement / Lunas)
INSERT INTO `web_hukum_pembayaran` (`id_pembayaran`, `id_formulir`, `order_id`, `jumlah_tagihan`, `metode_pembayaran`, `transaction_id`, `snap_token`, `payment_url`, `status`, `status_transaksi`, `bukti_bayar`, `keterangan`, `waktu_transaksi`, `created_at`, `updated_at`) VALUES
(1, 1, 'ORDER-HKM-20261112-0001', 500000.00, 'qris', 'TRX-QRIS-20261112-9901', NULL, NULL, 1, 'settlement', NULL, 'Pembayaran Konsultasi Awal & Registrasi', '2026-11-12 11:00:00', '2026-11-12 10:30:00', '2026-11-12 11:00:00'),
(2, 2, 'ORDER-HKM-20260811-0002', 15000000.00, 'bca_va', 'TRX-BCA-20260811-8821', NULL, NULL, 1, 'settlement', NULL, 'Honorer Advokat Penanganan Kasus Perdata Sengketa Tanah', '2026-08-11 14:20:00', '2026-08-11 12:00:00', '2026-08-11 14:20:00'),
(3, 3, 'ORDER-HKM-20260823-0003', 7500000.00, 'bni_va', 'TRX-BNI-20260823-3312', NULL, NULL, 1, 'settlement', NULL, 'Biaya Mediasi & Kuasa Hukum Perkara Waris', '2026-08-23 16:45:00', '2026-08-23 15:10:00', '2026-08-23 16:45:00'),
(4, 4, 'ORDER-HKM-20260906-0004', 25000000.00, 'bri_va', 'TRX-BRI-20260906-5541', NULL, NULL, 1, 'settlement', NULL, 'Honorarium Pembelaan Sidang Tipikor PN Tanjungkarang', '2026-09-06 11:30:00', '2026-09-06 09:30:00', '2026-09-06 11:30:00'),
(5, 5, 'ORDER-HKM-20261118-0005', 4500000.00, 'qris', 'TRX-QRIS-20261118-7712', NULL, NULL, 1, 'settlement', NULL, 'Biaya Pendampingan Mediasi Disnaker & Penelaahan Dokumen PHI', '2026-11-18 14:15:00', '2026-11-18 13:40:00', '2026-11-18 14:15:00');

-- Data Chat Konsultasi
INSERT INTO `web_hukum_chat` (`id_chat`, `id_pengirim`, `id_penerima`, `id_formulir`, `pesan`, `is_read`, `created_at`) VALUES
(1, 8, 1, 1, 'Selamat pagi Admin, berkas pengajuan saya sudah terkirim. Mohon dicek ya.', 1, '2026-11-12 10:15:00'),
(2, 1, 8, 1, 'Selamat pagi Pak Firhan. Berkas sudah kami verifikasi dan telah ditugaskan kepada Advokat Yopi Hendro, S.H., M.H.', 1, '2026-11-12 10:20:00'),
(3, 3, 8, 1, 'Halo Mas Firhan, saya Yopi Hendro. Jadwal konsultasi tatap muka kita jadwalkan tanggal 20 November pukul 10.00 WIB ya.', 0, '2026-11-12 10:45:00');

SET FOREIGN_KEY_CHECKS = 1;

