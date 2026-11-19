<?php
// Script untuk membuat database/database.sqlite lengkap untuk Vercel
$sqliteFile = __DIR__ . '/database/database.sqlite';
if (file_exists($sqliteFile)) {
    unlink($sqliteFile);
}

$pdo = new PDO('sqlite:' . $sqliteFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Aktifkan foreign keys
$pdo->exec("PRAGMA foreign_keys = ON;");

// 1. web_hukum_role
$pdo->exec("CREATE TABLE IF NOT EXISTS web_hukum_role (
    id_role INTEGER PRIMARY KEY AUTOINCREMENT,
    nama_role VARCHAR(50) NOT NULL,
    deskripsi VARCHAR(255) DEFAULT NULL
);");

// 2. web_hukum_klasterisasi
$pdo->exec("CREATE TABLE IF NOT EXISTS web_hukum_klasterisasi (
    id_klasterisasi INTEGER PRIMARY KEY AUTOINCREMENT,
    nama_klasterisasi VARCHAR(100) NOT NULL,
    keterangan TEXT DEFAULT NULL
);");

// 3. web_hukum_user
$pdo->exec("CREATE TABLE IF NOT EXISTS web_hukum_user (
    id_user INTEGER PRIMARY KEY AUTOINCREMENT,
    id_role INTEGER NOT NULL,
    name VARCHAR(128) NOT NULL,
    username VARCHAR(128) NOT NULL UNIQUE,
    image VARCHAR(128) NOT NULL DEFAULT 'default.jpg',
    password VARCHAR(256) NOT NULL,
    is_active INTEGER NOT NULL DEFAULT 1,
    date_created INTEGER NOT NULL,
    FOREIGN KEY (id_role) REFERENCES web_hukum_role(id_role) ON UPDATE CASCADE
);");

// 4. web_hukum_formulir_konsultasi
$pdo->exec("CREATE TABLE IF NOT EXISTS web_hukum_formulir_konsultasi (
    id_formulir INTEGER PRIMARY KEY AUTOINCREMENT,
    id_user INTEGER NOT NULL,
    id_user_pengacara INTEGER DEFAULT NULL,
    id_klasterisasi INTEGER DEFAULT NULL,
    no_permohonan VARCHAR(100) NOT NULL UNIQUE,
    nama VARCHAR(255) NOT NULL,
    tempat_lahir VARCHAR(100) DEFAULT NULL,
    tgl_lahir DATE DEFAULT NULL,
    jenis_kelamin VARCHAR(20) DEFAULT NULL,
    agama VARCHAR(50) DEFAULT NULL,
    alamat TEXT DEFAULT NULL,
    no_hp VARCHAR(30) DEFAULT NULL,
    pekerjaan VARCHAR(100) DEFAULT NULL,
    waktu_pelaksanaan DATE DEFAULT NULL,
    uraian_singkat_perkara TEXT DEFAULT NULL,
    ttd_pemohon VARCHAR(255) DEFAULT NULL,
    ttd_konsultan VARCHAR(255) DEFAULT NULL,
    bukti_awal VARCHAR(255) DEFAULT NULL,
    status_verifikasi INTEGER NOT NULL DEFAULT 0,
    status_kasus_pengacara INTEGER NOT NULL DEFAULT 0,
    status_jadwal_temu INTEGER NOT NULL DEFAULT 0,
    tgl_waktu_jadwal_temu DATETIME DEFAULT NULL,
    catatan_jadwal_temu VARCHAR(255) DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES web_hukum_user(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_user_pengacara) REFERENCES web_hukum_user(id_user) ON DELETE SET NULL,
    FOREIGN KEY (id_klasterisasi) REFERENCES web_hukum_klasterisasi(id_klasterisasi) ON DELETE SET NULL
);");

// 5. web_hukum_kontrak_kesepakatan
$pdo->exec("CREATE TABLE IF NOT EXISTS web_hukum_kontrak_kesepakatan (
    id_kontrak INTEGER PRIMARY KEY AUTOINCREMENT,
    id_formulir INTEGER NOT NULL,
    keterangan VARCHAR(255) DEFAULT NULL,
    berkas_kontrak VARCHAR(255) DEFAULT NULL,
    status_sidang INTEGER NOT NULL DEFAULT 0,
    created_at DATE NOT NULL,
    FOREIGN KEY (id_formulir) REFERENCES web_hukum_formulir_konsultasi(id_formulir) ON DELETE CASCADE
);");

// 6. web_hukum_pembayaran
$pdo->exec("CREATE TABLE IF NOT EXISTS web_hukum_pembayaran (
    id_pembayaran INTEGER PRIMARY KEY AUTOINCREMENT,
    id_formulir INTEGER NOT NULL,
    order_id VARCHAR(100) NOT NULL UNIQUE,
    jumlah_tagihan DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    metode_pembayaran VARCHAR(50) DEFAULT NULL,
    transaction_id VARCHAR(255) DEFAULT NULL,
    snap_token VARCHAR(255) DEFAULT NULL,
    payment_url VARCHAR(255) DEFAULT NULL,
    status INTEGER NOT NULL DEFAULT 0,
    status_transaksi VARCHAR(50) NOT NULL DEFAULT 'pending',
    bukti_bayar VARCHAR(255) DEFAULT NULL,
    keterangan TEXT DEFAULT NULL,
    waktu_transaksi DATETIME DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT NULL,
    FOREIGN KEY (id_formulir) REFERENCES web_hukum_formulir_konsultasi(id_formulir) ON DELETE CASCADE
);");

// 7. web_hukum_chat
$pdo->exec("CREATE TABLE IF NOT EXISTS web_hukum_chat (
    id_chat INTEGER PRIMARY KEY AUTOINCREMENT,
    id_pengirim INTEGER NOT NULL,
    id_penerima INTEGER NOT NULL,
    id_formulir INTEGER DEFAULT NULL,
    pesan TEXT NOT NULL,
    is_read INTEGER NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pengirim) REFERENCES web_hukum_user(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_penerima) REFERENCES web_hukum_user(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_formulir) REFERENCES web_hukum_formulir_konsultasi(id_formulir) ON DELETE SET NULL
);");

// 8. sessions, cache, jobs
$pdo->exec("CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id INTEGER DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);");

$pdo->exec("CREATE TABLE IF NOT EXISTS cache (
    key VARCHAR(255) PRIMARY KEY,
    value TEXT NOT NULL,
    expiration INTEGER NOT NULL
);");

// SEED ROLES
$pdo->exec("INSERT INTO web_hukum_role (id_role, nama_role, deskripsi) VALUES
(1, 'Sekretaris (Admin)', 'Pengelola sistem, verifikator berkas, chat helpdesk, dan update progres perkara'),
(2, 'Pengacara / Advokat', 'Penanggung jawab perkara hukum, konsultasi, dan persidangan'),
(3, 'Klien / Pemohon', 'Pengaju konsultasi hukum, pemohon perkara, dan pembayar tagihan'),
(4, 'Pimpinan / Managing Partner', 'Pimpinan kantor hukum Handoko & Partners');");

// SEED KLASTERISASI
$pdo->exec("INSERT INTO web_hukum_klasterisasi (id_klasterisasi, nama_klasterisasi, keterangan) VALUES
(1, 'Tindak Pidana Korupsi (Tipikor)', 'Perkara dugaan tindak pidana korupsi dan gratifikasi'),
(2, 'Tata Usaha Negara (TUN)', 'Sengketa kebijakan pejabat/badan tata usaha negara'),
(3, 'Hukum Perdata', 'Sengketa wanprestasi, perjanjian, ganti rugi, waris, dan sengketa tanah'),
(4, 'Hukum Pidana', 'Pendampingan perkara tindak pidana umum dan khusus'),
(5, 'Perbankan & Keuangan', 'Sengketa kredit macet, jaminan hak tanggungan, dan transaksi perbankan'),
(6, 'Sengketa Pemilukada / Pilkada', 'Sengketa perselisihan hasil pemilu kepala daerah dan legislatif'),
(7, 'Perusahaan, Bisnis & Pajak', 'Hukum korporasi, legal audit, ketenagakerjaan, dan sengketa pajak');");

// Hash for password123
$passHash = password_hash('password123', PASSWORD_BCRYPT);
$now = time();

// SEED USERS
$users = [
    // Admin
    [1, 1, 'Sekretaris Kantor', 'admin', 'default.jpg', $passHash, 1, $now],
    // Pimpinan
    [2, 4, 'Ahmad Handoko, S.H., M.H.', 'pimpinan', 'default.jpg', $passHash, 1, $now],
    // Pengacara
    [3, 2, 'Yopi Hendro, S.H., M.H.', 'pengacara1', 'default.jpg', $passHash, 1, $now],
    [4, 2, 'Ahmad Handoko, S.H., M.H.', 'ahmad_handoko', 'default.jpg', $passHash, 1, $now],
    [5, 2, 'M. Reza Pahlevi, S.H., M.H.', 'reza_pahlevi', 'default.jpg', $passHash, 1, $now],
    [6, 2, 'Dian Safitri, S.H., M.Kn.', 'dian_safitri', 'default.jpg', $passHash, 1, $now],
    [7, 2, 'Fajar Ramadhan, S.H.', 'fajar_ramadhan', 'default.jpg', $passHash, 1, $now],
    // Klien
    [8, 3, 'Firhan Danuarta', 'klien1', 'default.jpg', $passHash, 1, $now],
    [9, 3, 'Budi Santoso', 'klien2', 'default.jpg', $passHash, 1, $now],
    [10, 3, 'Siti Nurhaliza', 'klien3', 'default.jpg', $passHash, 1, $now],
    [11, 3, 'Andi Wijaya', 'klien4', 'default.jpg', $passHash, 1, $now],
    [12, 3, 'Hendra Kurniawan', 'klien5', 'default.jpg', $passHash, 1, $now],
];

$stmtUser = $pdo->prepare("INSERT INTO web_hukum_user (id_user, id_role, name, username, image, password, is_active, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($users as $u) {
    $stmtUser->execute($u);
}

// SEED FORMULIR KONSULTASI / CASES
$cases = [
    [
        1, 8, 3, 4, 'REG-HKM-20261112-0001', 'Firhan Danuarta', 'Metro', '2003-05-14', 'Laki-laki', 'Islam',
        'Jl. Ki Hajar Dewantara No. 15, Metro Timur', '081234567890', 'Mahasiswa / Wiraswasta', '2026-11-20',
        'Pendampingan hukum dugaan tindak pidana sengketa hak cipta perangkat lunak.',
        'ttd_pemohon_3_1794465291.png', 'ttd_konsultan_2_1794465618.png', null,
        1, 1, 2, '2026-11-20 10:00:00', 'Konsultasi Ruang Mediasi 1', '2026-11-12 10:00:00'
    ],
    [
        2, 9, 5, 3, 'REG-HKM-20260810-SLS01', 'Budi Santoso', 'Bandar Lampung', '1985-04-12', 'Laki-laki', 'Islam',
        'Jl. Raden Intan No. 45, Bandar Lampung', '082198765432', 'Pengusaha Properti', '2026-08-15',
        'Sengketa wanprestasi perjanjian jual beli tanah dan bangunan seluas 1.200 m2 di Teluk Betung.',
        'ttd_pemohon_3_1794465291.png', 'ttd_konsultan_2_1794465618.png', null,
        1, 2, 3, '2026-08-15 09:30:00', 'Selesai Sidang Putusan Pengadilan Negeri', '2026-08-10 08:30:00'
    ],
    [
        3, 10, 6, 3, 'REG-HKM-20260822-SLS02', 'Siti Nurhaliza', 'Metro', '1990-11-28', 'Perempuan', 'Islam',
        'Jl. Ahmad Yani No. 12, Kota Metro', '085712348765', 'PNS Daerah', '2026-08-28',
        'Gugatan pembagian harta bersama dan waris keluarga besar almarhum H. Mansyur.',
        'ttd_pemohon_3_1794465291.png', 'ttd_konsultan_2_1794465618.png', null,
        1, 2, 3, '2026-08-28 14:00:00', 'Mediasi Pengadilan Agama Berhasil', '2026-08-22 13:15:00'
    ],
    [
        4, 11, 4, 1, 'REG-HKM-20260905-SLS03', 'Andi Wijaya', 'Kalianda', '1978-02-15', 'Laki-laki', 'Kristen',
        'Jl. Lintas Sumatera Km. 35, Kalianda', '081399887766', 'Direktur CV Mandiri Jaya', '2026-09-10',
        'Pendampingan penyidikan tindak pidana korupsi pengadaan barang dan jasa APBD.',
        'ttd_pemohon_3_1794465291.png', 'ttd_konsultan_2_1794465618.png', null,
        1, 2, 3, '2026-09-10 10:00:00', 'Putusan Bebas PN Tipikor Tanjungkarang', '2026-09-05 09:00:00'
    ],
    [
        5, 12, 7, 7, 'REG-HKM-20261118-0005', 'Hendra Kurniawan', 'Bandar Lampung', '1982-07-20', 'Laki-laki', 'Islam',
        'Jl. Kartini No. 88, Tanjung Karang Pusat', '081922334455', 'Manajer Operasional PT Jaya Abadi', '2026-11-22',
        'Perselisihan Hubungan Industrial (PHI) pemutusan hubungan kerja sepihak 12 karyawan.',
        'ttd_pemohon_3_1794465291.png', 'ttd_konsultan_2_1794465618.png', null,
        1, 1, 2, '2026-11-22 13:30:00', 'Agenda Sidang Mediasi Dinaskertrans', '2026-11-18 11:20:00'
    ],
];

$stmtCase = $pdo->prepare("INSERT INTO web_hukum_formulir_konsultasi (
    id_formulir, id_user, id_user_pengacara, id_klasterisasi, no_permohonan, nama, tempat_lahir, tgl_lahir, jenis_kelamin, agama,
    alamat, no_hp, pekerjaan, waktu_pelaksanaan, uraian_singkat_perkara, ttd_pemohon, ttd_konsultan, bukti_awal,
    status_verifikasi, status_kasus_pengacara, status_jadwal_temu, tgl_waktu_jadwal_temu, catatan_jadwal_temu, created_at
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

foreach ($cases as $c) {
    $stmtCase->execute($c);
}

// SEED KONTRAK
$contracts = [
    [1, 1, 'Perjanjian Jasa Hukum Pendampingan Pidana Hak Cipta', 'kontrak_0001.pdf', 1, '2026-11-12'],
    [2, 2, 'Perjanjian Retainer Litigasi Sengketa Tanah Perdata', 'kontrak_0002.pdf', 4, '2026-08-11'],
    [3, 3, 'Perjanjian Jasa Hukum Non-Litigasi & Mediasi Waris', 'kontrak_0003.pdf', 4, '2026-08-23'],
    [4, 4, 'Perjanjian Pembelaan Perkara Tipikor', 'kontrak_0004.pdf', 4, '2026-09-06'],
    [5, 5, 'Perjanjian Pendampingan Kasus Ketenagakerjaan PHI', 'kontrak_0005.pdf', 2, '2026-11-18'],
];
$stmtKontrak = $pdo->prepare("INSERT INTO web_hukum_kontrak_kesepakatan (id_kontrak, id_formulir, keterangan, berkas_kontrak, status_sidang, created_at) VALUES (?, ?, ?, ?, ?, ?)");
foreach ($contracts as $k) {
    $stmtKontrak->execute($k);
}

// SEED PEMBAYARAN
$payments = [
    [1, 1, 'ORDER-HKM-20261112-0001', 500000.00, 'qris', 'TRX-QRIS-20261112-9901', null, null, 1, 'settlement', null, 'Pembayaran Konsultasi Awal & Registrasi', '2026-11-12 11:00:00', '2026-11-12 10:30:00', '2026-11-12 11:00:00'],
    [2, 2, 'ORDER-HKM-20260811-0002', 15000000.00, 'bca_va', 'TRX-BCA-20260811-8821', null, null, 1, 'settlement', null, 'Honorer Advokat Penanganan Kasus Perdata Sengketa Tanah', '2026-08-11 14:20:00', '2026-08-11 12:00:00', '2026-08-11 14:20:00'],
    [3, 3, 'ORDER-HKM-20260823-0003', 7500000.00, 'bni_va', 'TRX-BNI-20260823-3312', null, null, 1, 'settlement', null, 'Biaya Mediasi & Kuasa Hukum Perkara Waris', '2026-08-23 16:45:00', '2026-08-23 15:10:00', '2026-08-23 16:45:00'],
    [4, 4, 'ORDER-HKM-20260906-0004', 25000000.00, 'bri_va', 'TRX-BRI-20260906-5541', null, null, 1, 'settlement', null, 'Honorarium Pembelaan Sidang Tipikor PN Tanjungkarang', '2026-09-06 11:30:00', '2026-09-06 09:30:00', '2026-09-06 11:30:00'],
    [5, 5, 'ORDER-HKM-20261118-0005', 4500000.00, 'qris', 'TRX-QRIS-20261118-7712', null, null, 1, 'settlement', null, 'Biaya Pendampingan Mediasi Disnaker & Penelaahan Dokumen PHI', '2026-11-18 14:15:00', '2026-11-18 13:40:00', '2026-11-18 14:15:00'],
];

$stmtPay = $pdo->prepare("INSERT INTO web_hukum_pembayaran (
    id_pembayaran, id_formulir, order_id, jumlah_tagihan, metode_pembayaran, transaction_id, snap_token, payment_url,
    status, status_transaksi, bukti_bayar, keterangan, waktu_transaksi, created_at, updated_at
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

foreach ($payments as $p) {
    $stmtPay->execute($p);
}

// SEED CHAT
$chats = [
    [1, 8, 1, 1, 'Selamat pagi Admin, berkas pengajuan saya sudah terkirim. Mohon dicek ya.', 1, '2026-11-12 10:15:00'],
    [2, 1, 8, 1, 'Selamat pagi Pak Firhan. Berkas sudah kami verifikasi dan telah ditugaskan kepada Advokat Yopi Hendro, S.H., M.H.', 1, '2026-11-12 10:20:00'],
    [3, 3, 8, 1, 'Halo Mas Firhan, saya Yopi Hendro. Jadwal konsultasi tatap muka kita jadwalkan tanggal 20 November pukul 10.00 WIB ya.', 0, '2026-11-12 10:45:00'],
];
$stmtChat = $pdo->prepare("INSERT INTO web_hukum_chat (id_chat, id_pengirim, id_penerima, id_formulir, pesan, is_read, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
foreach ($chats as $ch) {
    $stmtChat->execute($ch);
}

echo "SUCCESS: database/database.sqlite berhasil dibuat dengan skema & data lengkap!\n";
