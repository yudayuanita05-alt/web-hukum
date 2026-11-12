<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Chat;
use App\Models\FormulirKonsultasi;
use App\Models\Klasterisasi;
use App\Models\KontrakKesepakatan;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

echo "=== MENAMBAHKAN PENGACARA & KASUS SELESAI ===\n\n";

// 1. TAMBAH / UPDATE DAFTAR PENGACARA (Role 2)
$lawyersData = [
    [
        'username' => 'ahmad_handoko',
        'name' => 'Ahmad Handoko, S.H., M.H.',
        'image' => 'default.jpg',
    ],
    [
        'username' => 'pengacara1', // Yopi Hendro
        'name' => 'Yopi Hendro, S.H., M.H.',
        'image' => 'default.jpg',
    ],
    [
        'username' => 'reza_pahlevi',
        'name' => 'M. Reza Pahlevi, S.H., M.H.',
        'image' => 'default.jpg',
    ],
    [
        'username' => 'dian_safitri',
        'name' => 'Dian Safitri, S.H., M.Kn.',
        'image' => 'default.jpg',
    ],
    [
        'username' => 'fajar_ramadhan',
        'name' => 'Fajar Ramadhan, S.H.',
        'image' => 'default.jpg',
    ],
];

$lawyers = [];
foreach ($lawyersData as $ld) {
    $user = User::where('username', $ld['username'])->first();
    if (!$user) {
        $user = User::create([
            'id_role' => 2, // Pengacara
            'name' => $ld['name'],
            'username' => $ld['username'],
            'password' => Hash::make('password123'),
            'image' => $ld['image'],
            'is_active' => 1,
            'date_created' => time(),
        ]);
        echo "[+] Pengacara baru ditambahkan: {$user->name} ({$user->username})\n";
    } else {
        $user->name = $ld['name'];
        $user->password = Hash::make('password123');
        $user->is_active = 1;
        $user->id_role = 2;
        $user->save();
        echo "[*] Pengacara diperbarui: {$user->name} ({$user->username})\n";
    }
    $lawyers[$ld['username']] = $user;
}

// 2. TAMBAH / UPDATE KLIEN (Role 3)
$clientsData = [
    ['username' => 'klien1', 'name' => 'Firhan Danuarta', 'hp' => '081234567890'],
    ['username' => 'klien2', 'name' => 'Budi Santoso', 'hp' => '082198765432'],
    ['username' => 'klien3', 'name' => 'Siti Nurhaliza', 'hp' => '085712348765'],
    ['username' => 'klien4', 'name' => 'Andi Wijaya', 'hp' => '081399887766'],
    ['username' => 'klien5', 'name' => 'Hendra Kurniawan', 'hp' => '081922334455'],
];

$clients = [];
foreach ($clientsData as $cd) {
    $user = User::where('username', $cd['username'])->first();
    if (!$user) {
        $user = User::create([
            'id_role' => 3, // Klien
            'name' => $cd['name'],
            'username' => $cd['username'],
            'password' => Hash::make('password123'),
            'image' => 'default.jpg',
            'is_active' => 1,
            'date_created' => time(),
        ]);
        echo "[+] Klien baru ditambahkan: {$user->name} ({$user->username})\n";
    } else {
        $user->name = $cd['name'];
        $user->password = Hash::make('password123');
        $user->is_active = 1;
        $user->save();
        echo "[*] Klien diperbarui: {$user->name} ({$user->username})\n";
    }
    $clients[$cd['username']] = $user;
}

// Ambil admin user
$adminUser = User::where('id_role', 1)->first();

// File TTD sample (menggunakan file yang sudah ada di uploads/ttd)
$existingTtd = 'ttd_pemohon_3_1794465291.png';
$existingTtdKonsultan = 'ttd_konsultan_2_1794465618.png';

// 3. TAMBAHKAN KASUS-KASUS SELESAI (Lengkap dengan Kontrak, Pembayaran Settlement, & Chat)
$casesData = [
    [
        'no_permohonan' => 'REG-HKM-20260810-SLS01',
        'klien' => 'klien2', // Budi Santoso
        'pengacara' => 'reza_pahlevi', // M. Reza Pahlevi, S.H., M.H.
        'id_klasterisasi' => 3, // Hukum Perdata
        'nama' => 'Budi Santoso',
        'tempat_lahir' => 'Bandar Lampung',
        'tgl_lahir' => '1985-04-12',
        'jenis_kelamin' => 'Laki-laki',
        'agama' => 'Islam',
        'alamat' => 'Jl. Raden Intan No. 45, Enggal, Bandar Lampung',
        'no_hp' => '082198765432',
        'pekerjaan' => 'Wiraswasta / Pengusaha Properti',
        'waktu_pelaksanaan' => '2026-07-15',
        'uraian_singkat_perkara' => 'Sengketa tumpang tindih kepemilikan tanah seluas 1.200 m2 dan sertifikat ganda di kawasan Kemiling Bandar Lampung. Pihak lawan mengklaim tanah waris keluarga kami secara sepihak dan memasang plang klaim. Kami membutuhkan gugatan perdata pembatalan sertifikat dan penetapan hak milik sah.',
        'ttd_pemohon' => $existingTtd,
        'ttd_konsultan' => $existingTtdKonsultan,
        'bukti_awal' => null,
        'status_verifikasi' => 1, // Disetujui
        'status_kasus_pengacara' => 2, // SELESAI
        'status_jadwal_temu' => 3, // Selesai
        'tgl_waktu_jadwal_temu' => '2026-07-20 10:00:00',
        'catatan_jadwal_temu' => 'Konsultasi tatap muka di Ruang Advokat 1 Kantor Ahmad Handoko Law Office.',
        'created_at' => '2026-07-16 09:30:00',
        'kontrak' => [
            'keterangan' => 'Putusan Pengadilan Negeri Tanjung Karang No. 42/Pdt.G/2026/PN.Tjk telah berkekuatan hukum tetap (Inkracht). Sertifikat Penggugat dinyatakan sah dan gugatan lawan ditolak seutuhnya.',
            'status_sidang' => 4, // Inkracht
            'created_at' => '2026-07-22',
        ],
        'pembayaran' => [
            'order_id' => 'ORDER-HKM-20260718-BD01',
            'jumlah_tagihan' => 15000000.00,
            'metode_pembayaran' => 'bca_va',
            'transaction_id' => 'MIDTRANS-BCA-20260718-9921',
            'status' => 1, // Lunas
            'status_transaksi' => 'settlement',
            'keterangan' => 'Honorarium penanganan perkara sengketa perdata tanah tingkat pertama PN Tanjung Karang hingga putusan Inkracht.',
        ],
        'chats' => [
            ['from' => 'klien', 'text' => 'Selamat siang Pak Reza, berkas asli SHM No. 415 Kemiling sudah saya serahkan ke sekretariat.'],
            ['from' => 'pengacara', 'text' => 'Siang Pak Budi. Sudah kami verifikasi dan gugatan perdata sudah didaftarkan secara e-court di PN Tanjung Karang.'],
            ['from' => 'pengacara', 'text' => 'Kabar baik Pak Budi, sidang pembacaan putusan kemarin memenangkan kita sepenuhnya dan batas waktu banding lawan sudah lewat, perkara sudah Inkracht.'],
            ['from' => 'klien', 'text' => 'Alhamdulillah, terima kasih banyak Pak Reza dan tim Ahmad Handoko Law Office atas kerja kerasnya!'],
        ]
    ],
    [
        'no_permohonan' => 'REG-HKM-20260825-SLS02',
        'klien' => 'klien3', // Siti Nurhaliza
        'pengacara' => 'pengacara1', // Yopi Hendro, S.H., M.H.
        'id_klasterisasi' => 4, // Hukum Pidana
        'nama' => 'Siti Nurhaliza',
        'tempat_lahir' => 'Metro',
        'tgl_lahir' => '1990-11-20',
        'jenis_kelamin' => 'Perempuan',
        'agama' => 'Islam',
        'alamat' => 'Jl. Teuku Umar No. 112, Kedaton, Bandar Lampung',
        'no_hp' => '085712348765',
        'pekerjaan' => 'Manajer Keuangan',
        'waktu_pelaksanaan' => '2026-08-10',
        'uraian_singkat_perkara' => 'Pendampingan hukum korban dugaan tindak pidana penipuan dan penggelapan modal kerjasama investasi pengadaan bahan pokok senilai Rp 350.000.000,- dengan terlapor rekan bisnis yang melarikan dana usaha.',
        'ttd_pemohon' => $existingTtd,
        'ttd_konsultan' => $existingTtdKonsultan,
        'bukti_awal' => null,
        'status_verifikasi' => 1,
        'status_kasus_pengacara' => 2, // SELESAI
        'status_jadwal_temu' => 3, // Selesai
        'tgl_waktu_jadwal_temu' => '2026-08-12 14:00:00',
        'catatan_jadwal_temu' => 'Penyusunan Berita Acara Pendampingan Laporan Polisi di Polda Lampung.',
        'created_at' => '2026-08-11 11:15:00',
        'kontrak' => [
            'keterangan' => 'Putusan Pengadilan Negeri Kelas 1A Tanjung Karang No. 88/Pid.B/2026. Terdakwa divonis bersalah melanggar Pasal 378 KUHP dan seluruh aset ganti rugi dikembalikan kepada korban.',
            'status_sidang' => 3, // Putusan
            'created_at' => '2026-08-14',
        ],
        'pembayaran' => [
            'order_id' => 'ORDER-HKM-20260812-ST02',
            'jumlah_tagihan' => 12500000.00,
            'metode_pembayaran' => 'mandiri_va',
            'transaction_id' => 'MIDTRANS-MANDIRI-20260812-5541',
            'status' => 1,
            'status_transaksi' => 'settlement',
            'keterangan' => 'Biaya pendampingan hukum perkara pidana dari tahap Laporan Polisi, Penyidikan hingga Persidangan.',
        ],
        'chats' => [
            ['from' => 'klien', 'text' => 'Pak Yopi, bagaimana hasil gelar perkara di Polda hari ini?'],
            ['from' => 'pengacara', 'text' => 'Hasil gelar perkara sangat positif Bu Siti, berkas perkara sudah P21 dan dilimpahkan ke Kejaksaan Negeri untuk segera disidangkan.'],
            ['from' => 'klien', 'text' => 'Terima kasih Pak Yopi atas pendampingannya yang sangat intensif.'],
        ]
    ],
    [
        'no_permohonan' => 'REG-HKM-20260905-SLS03',
        'klien' => 'klien4', // Andi Wijaya
        'pengacara' => 'dian_safitri', // Dian Safitri, S.H., M.Kn.
        'id_klasterisasi' => 7, // Perusahaan, Bisnis & Pajak
        'nama' => 'Andi Wijaya',
        'tempat_lahir' => 'Palembang',
        'tgl_lahir' => '1982-08-05',
        'jenis_kelamin' => 'Laki-laki',
        'agama' => 'Kristen',
        'alamat' => 'Perumahan Villa Citra Blok C No. 8, Jagabaya, Bandar Lampung',
        'no_hp' => '081399887766',
        'pekerjaan' => 'Direktur Operasional',
        'waktu_pelaksanaan' => '2026-09-01',
        'uraian_singkat_perkara' => 'Sengketa Hubungan Industrial (PHI) antara 15 orang eks karyawan dengan manajemen perusahaan terkait penutupan kantor cabang dan kompensasi uang pesangon, uang penghargaan masa kerja, dan uang penggantian hak (UPH).',
        'ttd_pemohon' => $existingTtd,
        'ttd_konsultan' => $existingTtdKonsultan,
        'bukti_awal' => null,
        'status_verifikasi' => 1,
        'status_kasus_pengacara' => 2, // SELESAI
        'status_jadwal_temu' => 3, // Selesai
        'tgl_waktu_jadwal_temu' => '2026-09-03 09:30:00',
        'catatan_jadwal_temu' => 'Mediasi Bipartit di Ruang Rapat Kantor Ahmad Handoko Law Office.',
        'created_at' => '2026-09-01 14:20:00',
        'kontrak' => [
            'keterangan' => 'Penyelesaian Non-Litigasi: Kesepakatan Bersama (Perjanjian Bersama) ditandatangani kedua belah pihak dan didaftarkan pada Pengadilan Hubungan Industrial (PHI) PN Tanjung Karang. Hak pesangon cair 100%.',
            'status_sidang' => 0, // Non-Litigasi
            'created_at' => '2026-09-04',
        ],
        'pembayaran' => [
            'order_id' => 'ORDER-HKM-20260902-AW03',
            'jumlah_tagihan' => 8500000.00,
            'metode_pembayaran' => 'qris',
            'transaction_id' => 'MIDTRANS-QRIS-20260902-7712',
            'status' => 1,
            'status_transaksi' => 'settlement',
            'keterangan' => 'Honorarium mediasi dan legal drafting Perjanjian Bersama Hubungan Industrial.',
        ],
        'chats' => [
            ['from' => 'klien', 'text' => 'Bu Dian, pihak manajemen sudah setuju dengan draft angka kompensasi yang kita ajukan?'],
            ['from' => 'pengacara', 'text' => 'Betul Pak Andi, draft sudah disepakati tanpa potongan, hari Jumat kita tanda tangan Perjanjian Bersama di kantor ya.'],
            ['from' => 'klien', 'text' => 'Luar biasa, mediasi berjalan sangat cepat dan damai. Terima kasih Bu Dian!'],
        ]
    ],
    [
        'no_permohonan' => 'REG-HKM-20260918-SLS04',
        'klien' => 'klien5', // Hendra Kurniawan
        'pengacara' => 'ahmad_handoko', // Ahmad Handoko, S.H., M.H.
        'id_klasterisasi' => 2, // Tata Usaha Negara (TUN)
        'nama' => 'Hendra Kurniawan',
        'tempat_lahir' => 'Kotabumi',
        'tgl_lahir' => '1979-02-17',
        'jenis_kelamin' => 'Laki-laki',
        'agama' => 'Islam',
        'alamat' => 'Jl. Pangeran Antasari No. 89, Kedamaian, Bandar Lampung',
        'no_hp' => '081922334455',
        'pekerjaan' => 'Pengusaha Reklame & Media Luar Ruang',
        'waktu_pelaksanaan' => '2026-09-10',
        'uraian_singkat_perkara' => 'Gugatan sengketa Tata Usaha Negara terhadap Surat Keputusan Kepala Dinas terkait pencabutan izin penyelenggaraan titik reklame bando jalan yang dinilai cacat prosedur, melanggar asas kecermatan, dan bertentangan dengan Asas-Asas Umum Pemerintahan yang Baik (AUPB).',
        'ttd_pemohon' => $existingTtd,
        'ttd_konsultan' => $existingTtdKonsultan,
        'bukti_awal' => null,
        'status_verifikasi' => 1,
        'status_kasus_pengacara' => 2, // SELESAI
        'status_jadwal_temu' => 3, // Selesai
        'tgl_waktu_jadwal_temu' => '2026-09-12 11:00:00',
        'catatan_jadwal_temu' => 'Konsultasi hukum langsung bersama Founder Kantor Bapak Ahmad Handoko, S.H., M.H.',
        'created_at' => '2026-09-11 16:45:00',
        'kontrak' => [
            'keterangan' => 'Putusan PTUN Bandar Lampung No. 18/G/2026/PTUN.BL mengabulkan seluruh gugatan Penggugat, membatalkan SK objek sengketa, dan mewajibkan Tergugat menerbitkan kembali izin reklame yang sah. Status Inkracht.',
            'status_sidang' => 4, // Inkracht
            'created_at' => '2026-09-15',
        ],
        'pembayaran' => [
            'order_id' => 'ORDER-HKM-20260912-HK04',
            'jumlah_tagihan' => 25000000.00,
            'metode_pembayaran' => 'bni_va',
            'transaction_id' => 'MIDTRANS-BNI-20260912-8829',
            'status' => 1,
            'status_transaksi' => 'settlement',
            'keterangan' => 'Biaya penanganan sengketa Tata Usaha Negara (TUN) di PTUN Bandar Lampung hingga putusan berkekuatan hukum tetap.',
        ],
        'chats' => [
            ['from' => 'klien', 'text' => 'Pak Handoko, eksepsi kompetensi absolut dari pihak dinas apakah berbahaya untuk kelanjutan sidang?'],
            ['from' => 'pengacara', 'text' => 'Tidak perlu khawatir Pak Hendra, pada putusan sela majelis hakim menolak seluruh eksepsi tergugat karena objek sengketa murni merupakan Keputusan TUN yang konkret, individual, dan final.'],
            ['from' => 'pengacara', 'text' => 'Alhamdulillah hari ini majelis hakim membacakan putusan akhir: Gugatan kita dikabulkan seluruhnya dan SK pencabutan izin dinyatakan batal demi hukum!'],
            ['from' => 'klien', 'text' => 'Alhamdulillah, terima kasih banyak Pak Handoko atas pembelaan hukum yang sangat tegas dan berbobot!'],
        ]
    ],
    [
        'no_permohonan' => 'REG-HKM-20261001-SLS05',
        'klien' => 'klien1', // Firhan Danuarta
        'pengacara' => 'fajar_ramadhan', // Fajar Ramadhan, S.H.
        'id_klasterisasi' => 3, // Hukum Perdata
        'nama' => 'Firhan Danuarta',
        'tempat_lahir' => 'Metro',
        'tgl_lahir' => '2001-05-18',
        'jenis_kelamin' => 'Laki-laki',
        'agama' => 'Islam',
        'alamat' => 'Jl. Ki Hajar Dewantara No. 15, Iringmulyo, Metro Timur',
        'no_hp' => '081234567890',
        'pekerjaan' => 'Mahasiswa / Wirausahawan',
        'waktu_pelaksanaan' => '2026-09-25',
        'uraian_singkat_perkara' => 'Gugatan wanprestasi atas perjanjian sewa beli unit kendaraan niaga logistik operasional yang tidak diserahkan dokumen BPKB-nya setelah seluruh angsuran lunas dibayarkan.',
        'ttd_pemohon' => $existingTtd,
        'ttd_konsultan' => $existingTtdKonsultan,
        'bukti_awal' => null,
        'status_verifikasi' => 1,
        'status_kasus_pengacara' => 2, // SELESAI
        'status_jadwal_temu' => 3, // Selesai
        'tgl_waktu_jadwal_temu' => '2026-09-28 13:00:00',
        'catatan_jadwal_temu' => 'Penyerahan somasi hukum ke-2 dan persiapan mediasi perdamaian.',
        'created_at' => '2026-09-26 10:00:00',
        'kontrak' => [
            'keterangan' => 'Kesepakatan Perdamaian Akta Van Dading di depan Hakim Mediator PN Tanjung Karang. Dokumen asli BPKB telah diserahkan kembali kepada Klien dan ganti rugi keterlambatan lunas.',
            'status_sidang' => 4, // Inkracht
            'created_at' => '2026-09-30',
        ],
        'pembayaran' => [
            'order_id' => 'ORDER-HKM-20260927-FD05',
            'jumlah_tagihan' => 10000000.00,
            'metode_pembayaran' => 'gopay',
            'transaction_id' => 'MIDTRANS-GOPAY-20260927-4401',
            'status' => 1,
            'status_transaksi' => 'settlement',
            'keterangan' => 'Biaya somasi, mediasi pengadilan dan akta perdamaian perkara perdata wanprestasi.',
        ],
        'chats' => [
            ['from' => 'klien', 'text' => 'Halo Mas Fajar, apakah surat somasi kedua sudah dikirimkan ke pihak penjual?'],
            ['from' => 'pengacara', 'text' => 'Sudah Mas Firhan, somasi resmi berstempel kantor kita sudah diterima langsung oleh pimpinan mereka kemarin.'],
            ['from' => 'pengacara', 'text' => 'Pihak mereka langsung mengajukan perdamaian damai dan bersedia menyerahkan BPKB besok di hadapan notaris.'],
            ['from' => 'klien', 'text' => 'Mantap sekali Mas Fajar, cepat sekali responnya!'],
        ]
    ],
    // Kasus 6: Kasus Aktif / Dalam Progres (supaya ada variasi perkara berjalan)
    [
        'no_permohonan' => 'REG-HKM-20261105-PRG01',
        'klien' => 'klien1', // Firhan Danuarta
        'pengacara' => 'pengacara1', // Yopi Hendro, S.H., M.H.
        'id_klasterisasi' => 5, // Perbankan & Keuangan
        'nama' => 'Firhan Danuarta',
        'tempat_lahir' => 'Metro',
        'tgl_lahir' => '2001-05-18',
        'jenis_kelamin' => 'Laki-laki',
        'agama' => 'Islam',
        'alamat' => 'Jl. Ki Hajar Dewantara No. 15, Iringmulyo, Metro Timur',
        'no_hp' => '081234567890',
        'pekerjaan' => 'Mahasiswa / Wirausahawan',
        'waktu_pelaksanaan' => '2026-11-01',
        'uraian_singkat_perkara' => 'Pendampingan konsultasi hukum terkait restrukturisasi kredit modal kerja perbankan swasta yang terancam lelang eksekusi hak tanggungan oleh pihak bank tanpa melalui prosedur musyawarah debitur.',
        'ttd_pemohon' => $existingTtd,
        'ttd_konsultan' => $existingTtdKonsultan,
        'bukti_awal' => null,
        'status_verifikasi' => 1,
        'status_kasus_pengacara' => 1, // DALAM PROGRES
        'status_jadwal_temu' => 2, // Terjadwal
        'tgl_waktu_jadwal_temu' => '2026-11-20 10:30:00',
        'catatan_jadwal_temu' => 'Rapat koordinasi penundaan lelang dan restrukturisasi tenor bersama Bagian Legal Bank.',
        'created_at' => '2026-11-02 08:30:00',
        'kontrak' => [
            'keterangan' => 'Tahap negosiasi non-litigasi dan pengajuan permohonan penangguhan eksekusi hak tanggungan ke KPKNL Lampung.',
            'status_sidang' => 1, // Gugatan/Negosiasi
            'created_at' => '2026-11-04',
        ],
        'pembayaran' => [
            'order_id' => 'ORDER-HKM-20261103-FD06',
            'jumlah_tagihan' => 5000000.00,
            'metode_pembayaran' => 'qris',
            'transaction_id' => 'MIDTRANS-QRIS-20261103-1299',
            'status' => 1,
            'status_transaksi' => 'settlement',
            'keterangan' => 'Uang muka (DP) pendampingan hukum restrukturisasi kredit perbankan.',
        ],
        'chats' => [
            ['from' => 'klien', 'text' => 'Pak Yopi, surat permohonan penangguhan lelang ke KPKNL sudah diajukan?'],
            ['from' => 'pengacara', 'text' => 'Sudah Mas Firhan, tanda terima berkas resmi sudah kita pegang sehingga lelang otomatis ditunda.'],
        ]
    ]
];

foreach ($casesData as $cd) {
    $clientUser = $clients[$cd['klien']];
    $lawyerUser = $lawyers[$cd['pengacara']];

    $formulir = FormulirKonsultasi::where('no_permohonan', $cd['no_permohonan'])->first();
    if (!$formulir) {
        $formulir = FormulirKonsultasi::create([
            'id_user' => $clientUser->id_user,
            'id_user_pengacara' => $lawyerUser->id_user,
            'id_klasterisasi' => $cd['id_klasterisasi'],
            'no_permohonan' => $cd['no_permohonan'],
            'nama' => $cd['nama'],
            'tempat_lahir' => $cd['tempat_lahir'],
            'tgl_lahir' => $cd['tgl_lahir'],
            'jenis_kelamin' => $cd['jenis_kelamin'],
            'agama' => $cd['agama'],
            'alamat' => $cd['alamat'],
            'no_hp' => $cd['no_hp'],
            'pekerjaan' => $cd['pekerjaan'],
            'waktu_pelaksanaan' => $cd['waktu_pelaksanaan'],
            'uraian_singkat_perkara' => $cd['uraian_singkat_perkara'],
            'ttd_pemohon' => $cd['ttd_pemohon'],
            'ttd_konsultan' => $cd['ttd_konsultan'],
            'bukti_awal' => $cd['bukti_awal'],
            'status_verifikasi' => $cd['status_verifikasi'],
            'status_kasus_pengacara' => $cd['status_kasus_pengacara'],
            'status_jadwal_temu' => $cd['status_jadwal_temu'],
            'tgl_waktu_jadwal_temu' => $cd['tgl_waktu_jadwal_temu'],
            'catatan_jadwal_temu' => $cd['catatan_jadwal_temu'],
            'created_at' => $cd['created_at'],
        ]);
        echo "[+] Kasus baru dibuat: {$formulir->no_permohonan} - {$formulir->nama}\n";
    } else {
        $formulir->id_user_pengacara = $lawyerUser->id_user;
        $formulir->status_verifikasi = $cd['status_verifikasi'];
        $formulir->status_kasus_pengacara = $cd['status_kasus_pengacara'];
        $formulir->status_jadwal_temu = $cd['status_jadwal_temu'];
        $formulir->tgl_waktu_jadwal_temu = $cd['tgl_waktu_jadwal_temu'];
        $formulir->catatan_jadwal_temu = $cd['catatan_jadwal_temu'];
        $formulir->save();
        echo "[*] Kasus diperbarui: {$formulir->no_permohonan}\n";
    }

    // Buat / Update Kontrak
    KontrakKesepakatan::updateOrCreate(
        ['id_formulir' => $formulir->id_formulir],
        [
            'keterangan' => $cd['kontrak']['keterangan'],
            'berkas_kontrak' => null,
            'status_sidang' => $cd['kontrak']['status_sidang'],
            'created_at' => $cd['kontrak']['created_at'],
        ]
    );

    // Buat / Update Pembayaran
    Pembayaran::updateOrCreate(
        ['id_formulir' => $formulir->id_formulir],
        [
            'order_id' => $cd['pembayaran']['order_id'],
            'jumlah_tagihan' => $cd['pembayaran']['jumlah_tagihan'],
            'metode_pembayaran' => $cd['pembayaran']['metode_pembayaran'],
            'transaction_id' => $cd['pembayaran']['transaction_id'],
            'snap_token' => 'SNAP-' . strtoupper(Str::random(20)),
            'payment_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . Str::random(10),
            'status' => $cd['pembayaran']['status'],
            'status_transaksi' => $cd['pembayaran']['status_transaksi'],
            'waktu_transaksi' => $formulir->created_at,
            'bukti_bayar' => null,
            'keterangan' => $cd['pembayaran']['keterangan'],
            'created_at' => $formulir->created_at,
            'updated_at' => $formulir->created_at,
        ]
    );

    // Buat Chat riwayat
    foreach ($cd['chats'] as $chatItem) {
        $pengirimId = ($chatItem['from'] === 'klien') ? $clientUser->id_user : $lawyerUser->id_user;
        $penerimaId = ($chatItem['from'] === 'klien') ? $lawyerUser->id_user : $clientUser->id_user;

        // Cek apakah pesan sudah ada agar tidak duplikat
        $chatExists = Chat::where('id_formulir', $formulir->id_formulir)
            ->where('pesan', $chatItem['text'])
            ->exists();

        if (!$chatExists) {
            Chat::create([
                'id_pengirim' => $pengirimId,
                'id_penerima' => $penerimaId,
                'id_formulir' => $formulir->id_formulir,
                'pesan' => $chatItem['text'],
                'is_read' => 1,
                'created_at' => $formulir->created_at,
            ]);
        }
    }
}

echo "\n=== SEEDING BERHASIL SEPENUHNYA! ===\n";
echo "Total Pengacara aktif: " . User::where('id_role', 2)->count() . "\n";
echo "Total Klien aktif: " . User::where('id_role', 3)->count() . "\n";
echo "Total Perkara Konsultasi: " . FormulirKonsultasi::count() . "\n";
echo "Total Kasus Selesai: " . FormulirKonsultasi::where('status_kasus_pengacara', 2)->count() . "\n";
echo "Total Pembayaran Lunas: " . Pembayaran::where('status', 1)->count() . "\n";
?>
