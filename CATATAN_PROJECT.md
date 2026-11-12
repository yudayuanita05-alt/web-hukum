# CATATAN LENGKAP SPESIFIKASI PROYEK
*(Berdasarkan Dokumen Skripsi Lengkap BAB I s/d Struktur Database)*

---

## 1. Identitas Proyek & Stakeholder
- **Judul**: Rancang Bangun Sistem Informasi Layanan dan Konsultasi Hukum Berbasis Website pada Kantor Ahmad Handoko Law Office Bandar Lampung
- **Penyusun**: Firhan Danuarta (NPM: 22430035)
- **Program Studi**: S1 Ilmu Komputer, Fakultas Ilmu Komputer, Universitas Muhammadiyah Metro (2026)
- **Instansi Mitra**: Ahmad Handoko Law Office, Bandar Lampung
- **Narasumber Studi Lapangan**: Bapak Yopi Hendro, S.H., M.H. (Advokat/Praktisi Hukum di Ahmad Handoko Law Office)
- **Domain Rencana/Target**: `ahmadhandokolawoffice.web.id`

---

## 2. Masalah Riil & Temuan Observasi Lapangan
Berdasarkan hasil wawancara dengan **Bpk. Yopi Hendro, S.H., M.H.** dan observasi langsung:
1. **Pencatatan Konvensional**: Kantor masih menggunakan **metode pencatatan di buku besar** secara manual untuk mendata klien dan konsultasi.
2. **Keterbatasan Akses & Efisiensi**: Klien harus datang langsung ke kantor hanya untuk bertanya info layanan atau berkonsultasi, yang menyulitkan masyarakat luar daerah.
3. **Penyimpanan Berkas Fisik**: Berkas formulir permohonan, verifikasi, dan penanganan perkara menumpuk di lemari arsip, rawan rusak/hilang, dan sulit dilacak status perkembangannya secara cepat.
4. **Solusi Sistem**: Digitalisasi pencatatan, konsultasi interaktif via web (chat), transparansi progres penanganan perkara, serta pengarsipan data perkara digital.

---

## 3. Metodologi Pengembangan: Waterfall (Sommerville)
1. **Analisis Kebutuhan (Requirement Analysis)**: Mengidentifikasi kebutuhan fungsional 3 level pengguna, kebutuhan berkas, dan batasan pembayaran manual.
2. **Perancangan (Design)**: Merancang arsitektur MVC, diagram alir sistem, relasi basis data (ERD), dan antarmuka pengguna (UI/UX).
3. **Implementasi (Implementation)**: Penulisan kode program menggunakan PHP dengan Framework CodeIgniter dan basis data MySQL (XAMPP).
4. **Pengujian (Testing)**: Pengujian fungsionalitas menggunakan metode **Black Box Testing** dan pengujian pengguna dengan **Beta Testing**.
5. **Pemeliharaan (Maintenance)**: Penyesuaian fitur pasca deployment.

---

## 4. Pembatasan Masalah & Lingkup Fungsional Sistem

### A. Lingkup Frontend (Publik)
- Halaman Profil Kantor Hukum Ahmad Handoko Law Office (Visi, Misi, Sejarah, Struktur Organisasi).
- Informasi Layanan & Spesialisasi Hukum:
  - Tindak Pidana Korupsi (Tipikor)
  - Hukum Tata Usaha Negara (TUN)
  - Hukum Perdata & Pidana Umum
  - Hukum Perbankan & Keuangan
  - Sengketa Pemilukada / Pilkada (Gubernur, Bupati, DPR)
  - Hukum Bisnis, Korporasi, Perdagangan, Pajak, dan Real Estate
- Informasi Kontak & Lokasi Kantor Bandar Lampung.
- Modul Registrasi Klien & Login Sistem.

### B. Lingkup Backend (Tiga Level Pengguna / Hak Akses)

1. **User (Klien)**:
   - Registrasi dan login.
   - Mengajukan permohonan konsultasi hukum online (isi identitas, kronologi perkara, upload berkas bukti awal, dan bubuhkan tanda tangan digital).
   - Melakukan konsultasi langsung via **Fitur Chat di website**.
   - Melihat pembaruan (update) progres kasus yang sedang ditangani secara berkala (*dalam proses* / *selesai*).
   - Melihat rincian tagihan jasa hukum dan mengunggah bukti pembayaran transfer.

2. **Sekretaris (Admin)**:
   - Mengelola profil kantor advokat dan konten layanan.
   - **Merespons pesan/pertanyaan dari klien via Fitur Chat**.
   - Mengelola dan memverifikasi permohonan konsultasi masuk.
   - Menetapkan jadwal temu konsultasi.
   - **Meng-update status progres penanganan perkara klien secara berkala**.
   - Mengelola berkas kontrak kesepakatan penanganan perkara.
   - Memverifikasi bukti transfer pembayaran yang diunggah klien.
   - Mengakses rekapitulasi laporan kasus dan manajemen pengguna.

3. **Pengacara / Advokat**:
   - Melihat data klien yang ditangani.
   - Memantau progres perkara dan agenda persidangan.
   - **Melihat laporan rekapitulasi data kasus yang dihasilkan secara otomatis oleh sistem**.
   - Membubuhkan tanda tangan digital konsultan (`ttd_konsultan`).

### C. Batasan Berkas & Pembayaran (Diperbarui dengan Fitur Payment Gateway)
- Berkas yang diunggah dibatasi pada:
  1. Identitas Klien & Dokumen Bukti Awal Perkara.
  2. Berkas Kontrak Kesepakatan Jasa Hukum.
- **Sistem Pembayaran Terintegrasi Payment Gateway**:
  - Menggunakan integrasi Payment Gateway resmi (contoh: **Midtrans Snap / QRIS / Virtual Account / E-Wallet**).
  - Status transaksi diverifikasi otomatis via webhook/HTTP notification secara *real-time* (otomatis berubah menjadi *Settlement / Lunas* tanpa menunggu konfirmasi manual).
  - Menghasilkan `order_id` unik untuk setiap transaksi penanganan perkara.

---

## 5. Struktur Basis Data yang Telah Disempurnakan (Memenuhi 1NF - 3NF Penuh)

Berikut adalah skema basis data setelah dilakukan perbaikan kekurangan relasi dan normalisasi:

### 1. `web_hukum_role` (Tabel Master Role)
- **`id_role`** (INT 11, PK, Auto Increment)
- `nama_role` (VARCHAR 50)
- `deskripsi` (VARCHAR 255)

### 2. `web_hukum_klasterisasi` (Tabel Master Klasifikasi Kasus)
- **`id_klasterisasi`** (INT 11, PK, Auto Increment)
- `nama_klasterisasi` (VARCHAR 100)
- `keterangan` (TEXT)

### 3. `web_hukum_user`
- **`id_user`** (INT 11, PK, Auto Increment)
- `id_role` (INT 11, FK ke `web_hukum_role.id_role`)
- `name` (VARCHAR 128)
- `username` (VARCHAR 128, UNIQUE)
- `image` (VARCHAR 128)
- `password` (VARCHAR 256)
- `is_active` (INT 1) -> 1: Aktif, 0: Nonaktif
- `date_created` (INT 11)

### 4. `web_hukum_formulir_konsultasi`
- **`id_formulir`** (INT 11, PK, Auto Increment)
- `id_user` (INT 11, FK ke `web_hukum_user.id_user` - Klien)
- `id_user_pengacara` (INT 11, FK ke `web_hukum_user.id_user` - Advokat)
- `id_klasterisasi` (INT 11, FK ke `web_hukum_klasterisasi.id_klasterisasi`)
- `no_permohonan` (VARCHAR 100, UNIQUE)
- `nama`, `tempat_lahir`, `tgl_lahir` (DATE), `jenis_kelamin` (ENUM), `agama`, `alamat` (TEXT), `no_hp`, `pekerjaan`
- `waktu_pelaksanaan` (DATE)
- `uraian_singkat_perkara` (TEXT)
- `ttd_pemohon` (VARCHAR 255)
- `ttd_konsultan` (VARCHAR 255)
- `bukti_awal` (VARCHAR 255)
- `status_verifikasi` (INT 1) -> 0: Menunggu, 1: Disetujui, 2: Ditolak
- `status_kasus_pengacara` (INT 1) -> 0: Masuk, 1: Dalam Progres, 2: Selesai
- `status_jadwal_temu` (INT 1) -> 0: Belum Ada, 1: Menunggu, 2: Disetujui, 3: Selesai
- `tgl_waktu_jadwal_temu` (DATETIME)
- `catatan_jadwal_temu` (VARCHAR 255)
- `created_at` (DATETIME)

### 5. `web_hukum_kontrak_kesepakatan`
- **`id_kontrak`** (INT 11, PK, Auto Increment)
- `id_formulir` (INT 11, FK ke `web_hukum_formulir_konsultasi.id_formulir`)
- `keterangan` (VARCHAR 255)
- `berkas_kontrak` (VARCHAR 255)
- `status_sidang` (INT 1) -> 0: Non-Litigasi, 1: Pendaftaran Gugatan, 2: Pembuktian, 3: Putusan, 4: Inkracht
- `created_at` (DATE)

### 6. `web_hukum_pembayaran` (Modul Payment Gateway)
- **`id_pembayaran`** (INT 11, PK, Auto Increment)
- `id_formulir` (INT 11, FK ke `web_hukum_formulir_konsultasi.id_formulir`)
- `order_id` (VARCHAR 100, UNIQUE) -> *Kode transaksi unik sistem payment gateway*
- `jumlah_tagihan` (DECIMAL 12,2) -> *Besaran biaya tagihan jasa hukum*
- `metode_pembayaran` (VARCHAR 50) -> *e.g. qris, bca_va, bri_va, gopay, mandiri_va*
- `transaction_id` (VARCHAR 255) -> *ID transaksi resmi dari payment gateway*
- `snap_token` (VARCHAR 255) -> *Token popup Midtrans Snap*
- `payment_url` (VARCHAR 255) -> *URL redirect transaksi online*
- `status` (INT 1) -> 0: Belum Bayar/Pending, 1: Settlement/Lunas, 2: Expired, 3: Batal/Ditolak
- `status_transaksi` (VARCHAR 50) -> *Status gateway: pending, settlement, expire, cancel, deny*
- `bukti_bayar` (VARCHAR 255) -> *Path struk/invoice bukti pembayaran*
- `keterangan` (TEXT)
- `waktu_transaksi` (DATETIME) -> *Timestamp pelunasan transaksi dari notifikasi webhook*
- `created_at` (DATETIME)
- `updated_at` (DATETIME)

### 7. `web_hukum_chat`
- **`id_chat`** (INT 11, PK, Auto Increment)
- `id_pengirim` (INT 11, FK ke `web_hukum_user.id_user`)
- `id_penerima` (INT 11, FK ke `web_hukum_user.id_user`)
- `id_formulir` (INT 11, FK ke `web_hukum_formulir_konsultasi.id_formulir`)
- `pesan` (TEXT)
- `is_read` (INT 1)
- `created_at` (DATETIME)

---

### Aturan Konvensi Penamaan Primary Key (Wajib Dipatuhi)
Sesuai instruksi dan standar perancangan basis data sistem ini:
- **Setiap tabel WAJIB mendefinisikan Primary Key eksplisit dengan pola `id_<nama_tabel / entitas>`**.
- **DILARANG menggunakan nama kolom generik seperti hanya `id` saja**.
- Daftar Primary Key pada proyek ini:
  1. Tabel `web_hukum_user` ➔ Primary Key: **`id_user`**
  2. Tabel `web_hukum_formulir_konsultasi` ➔ Primary Key: **`id_formulir`**
  3. Tabel `web_hukum_kontrak_kesepakatan` ➔ Primary Key: **`id_kontrak`**
  4. Tabel `web_hukum_pembayaran` ➔ Primary Key: **`id_pembayaran`**
  5. Tabel `web_hukum_chat` ➔ Primary Key: **`id_chat`**
  *(Bila di kemudian hari ada penambahan tabel master, misalnya tabel kelas/role/kategori, maka Primary Key-nya wajib mengikuti pola yang sama, contoh: `id_kelas`, `id_role`, `id_kategori`).*

---

## 6. Sistematika Skripsi Terkait
- **BAB I**: Pendahuluan (Latar Belakang, Buku Besar manual, Rumusan Masalah, Batasan 3 Role & Pembayaran Manual).
- **BAB II**: Kajian Literatur (Teori Web, Waterfall, CodeIgniter MVC, PHP, MySQL).
- **BAB III**: Gambaran Organisasi (Profil Ahmad Handoko Law Office, Wawancara Bpk. Yopi Hendro, S.H., M.H., Struktur Organisasi).
- **BAB IV**: Hasil dan Pembahasan (Analisis Kebutuhan, Implementasi MVC, Pengujian Black Box Testing).
- **BAB V**: Penutup (Kesimpulan dan Saran).


---

## 7. Analisis Normalisasi Basis Data (1NF s/d 3NF)

### A. Pengujian 1NF (Bentuk Normal Pertama)
- **Status**: **MEMENUHI 1NF (Lolos)**
- **Alasan**: 
  - Seluruh kolom menyimpan nilai atomik/tunggal (tidak ada kolom array atau multivalue).
  - Setiap tabel memiliki Primary Key tunggal berbasis integer auto-increment (`id_user`, `id_formulir`, `id_kontrak`, `id_pembayaran`).
  - Tidak ada pengulangan kelompok kolom (*no repeating groups*).

### B. Pengujian 2NF (Bentuk Normal Kedua)
- **Status**: **MEMENUHI 2NF (Lolos)**
- **Alasan**:
  - Telah memenuhi 1NF.
  - Seluruh tabel menggunakan **Single Primary Key** (bukan *composite primary key*).
  - Secara teori matematis, ketergantungan sebagian (*Partial Functional Dependency*) tidak mungkin terjadi pada tabel ber-kunci primer tunggal, sehingga semua atribut non-kunci bergantung penuh pada Primary Key masing-masing.

### C. Pengujian 3NF (Bentuk Normal Ketiga)
- **Status**: **SEMI-3NF / DENORMALISASI TERUKUR**
- **Temuan Dependensi Transitif & Redundansi**:
  1. **Tabel `web_hukum_pembayaran`**: Memiliki `id_user` dan `id_formulir` sekaligus. Karena `id_formulir` sudah merujuk ke `id_user` di tabel formulir, maka terdapat ketergantungan transitif:
     $$\text{id\_pembayaran} \to \text{id\_formulir} \to \text{id\_user}$$
     *(Kolom `id_user` sebenarnya bersifat redundan)*.
  2. **Tabel `web_hukum_formulir_konsultasi`**: Memiliki `id_user`, namun tetap menyimpan data pemohon (`nama`, `alamat`, `no_hp`).
  3. **Kolom `id_user_pengacara`**: Bertipe `VARCHAR(255)` dan belum dijadikan Foreign Key formal bertipe `INT(11)` yang merujuk ke `web_hukum_user(id_user)`.
- **Argumen Pembelaan Akademis (Sidang/Ujian)**:
  - Data identitas pada formulir hukum merupakan **snapshot data legal** yang bersifat tetap pada saat pengajuan perkara, sehingga tidak boleh berubah jika klien mengubah profil akunnya di masa depan.
  - Kolom `id_user` pada tabel pembayaran dipertahankan untuk mengoptimalkan performa query rekapitulasi keuangan tanpa operasi JOIN bertingkat.

---

## 8. Matriks Hak Akses Pengguna (Access Control Matrix)

| Fitur / Modul Sistem | Pengunjung Publik | Klien (Role 3) | Sekretaris/Admin (Role 1) | Pengacara (Role 2) |
| :--- | :---: | :---: | :---: | :---: |
| Lihat Profil & Layanan Kantor | ✅ | ✅ | ✅ | ✅ |
| Registrasi Akun Klien | ✅ | ❌ | ❌ | ❌ |
| Login & Ganti Password Akun | ❌ | ✅ | ✅ | ✅ |
| Isi Form Konsultasi & Upload Bukti | ❌ | ✅ (Milik Sendiri) | ❌ | ❌ |
| TTD Digital Pemohon (`ttd_pemohon`) | ❌ | ✅ | ❌ | ❌ |
| TTD Digital Konsultan (`ttd_konsultan`) | ❌ | ❌ | ❌ | ✅ |
| Verifikasi Form & Klasterisasi Kasus | ❌ | ❌ | ✅ | ❌ |
| Penjadwalan Temu Konsultasi | ❌ | ✅ (Lihat Jadwal) | ✅ (Atur Jadwal) | ✅ (Lihat Jadwal) |
| Fitur Chat Konsultasi Online | ❌ | ✅ (Chat ke Admin) | ✅ (Balas Chat Klien)| ❌ |
| Update Progres Kasus | ❌ | ❌ (Hanya Melihat) | ✅ (Update Status) | ❌ (Hanya Melihat) |
| Kelola Dokumen Kontrak Kesepakatan | ❌ | ✅ (Unduh/Lihat) | ✅ (Upload/Kelola) | ✅ (Lihat) |
| Update Status Jadwal Sidang | ❌ | ❌ (Hanya Melihat) | ✅ | ✅ (Melihat/Memantau)|
| Upload Bukti Pembayaran Tagihan | ❌ | ✅ | ❌ | ❌ |
| Verifikasi Status Pembayaran | ❌ | ❌ | ✅ | ❌ |
| Rekapitulasi Laporan Kasus Otomatis | ❌ | ❌ | ✅ | ✅ |
| Manajemen Data User & Profil Kantor | ❌ | ❌ | ✅ | ❌ |

---

## 9. Rencana Arsitektur Folder CodeIgniter (MVC)

```text
application/
├── config/
│   ├── autoload.php          # Session, database, url helper, form_validation
│   ├── config.php            # Base URL, encryption key
│   ├── database.php          # Koneksi MySQL/MariaDB XAMPP
│   └── routes.php            # Routing default & custom url
├── controllers/
│   ├── Home.php              # Landing page, profil kantor, katalog layanan
│   ├── Auth.php              # Login, register klien, logout, proteksi session
│   ├── Klien/
│   │   ├── Dashboard.php     # Ringkasan status konsultasi & perkara
│   │   ├── Konsultasi.php    # Form pengajuan konsultasi + canvas TTD digital
│   │   ├── Chat.php          # Komunikasi chat online dengan sekretaris
│   │   ├── Progres.php       # Timeline pemantauan kasus & agenda sidang
│   │   └── Pembayaran.php    # Upload bukti transfer pembayaran
│   ├── Admin/
│   │   ├── Dashboard.php     # Statistik ringkasan perkara & pendapatan
│   │   ├── Profil_kantor.php # Kelola konten landing page & layanan
│   │   ├── Konsultasi.php    # Verifikasi berkas, klasterisasi, jadwal temu
│   │   ├── Chat.php          # Helpdesk chat menanggapi klien
│   │   ├── Kasus.php         # Update progres penanganan perkara & sidang
│   │   ├── Kontrak.php       # Kelola file kontrak kesepakatan
│   │   ├── Pembayaran.php    # Konfirmasi validasi bukti bayar
│   │   ├── Laporan.php       # Rekapitulasi laporan perkara & cetak PDF
│   │   └── User.php          # Manajemen akun klien, pengacara, staf
│   └── Pengacara/
│       ├── Dashboard.php     # Perkara aktif yang ditugaskan
│       ├── Kasus.php         # Detail kasus, dokumen perkara, TTD konsultan
│       └── Laporan.php       # Laporan rekapitulasi perkara otomatis
├── models/
│   ├── User_model.php        # Query akun & autentikasi
│   ├── Formulir_model.php    # Query permohonan konsultasi & status perkara
│   ├── Kontrak_model.php     # Query berkas kontrak & agenda sidang
│   ├── Pembayaran_model.php  # Query tagihan & bukti transfer
│   └── Chat_model.php        # Query simpan & ambil percakapan chat
├── views/
│   ├── auth/                 # Template login & registrasi
│   ├── frontend/             # Landing page publik (profil, layanan, kontak)
│   ├── klien/                # Views dashboard klien, form permohonan, chat, dll.
│   ├── admin/                # Views dashboard admin/sekretaris, verifikasi, dll.
│   ├── pengacara/            # Views dashboard advokat & laporan
│   └── templates/            # Header, sidebar, topbar, footer (Bootstrap/AdminLTE)
└── uploads/
    ├── bukti_awal/           # Berkas bukti kasus pemohon
    ├── ttd/                  # File PNG tanda tangan digital pemohon & konsultan
    ├── kontrak/              # Dokumen PDF kontrak kesepakatan
    ├── bukti_bayar/          # Gambar struk/bukti transfer bank
    └── profile/              # Foto profil user
```


---

## 10. Perancangan Alur Sistem & Logika Proses (Flowchart Deskriptif)

### A. Alur Pengajuan Konsultasi Online & Tanda Tangan Digital
1. Klien melakukan login dan masuk ke menu **Pengajuan Konsultasi**.
2. Sistem otomatis mengenerate `no_permohonan` unik (misal: `REG-YYYYMMDD-XXXX`).
3. Klien mengisi data diri pemohon, uraian singkat perkara hukum, dan mengunggah berkas bukti awal (`bukti_awal`).
4. Klien membubuhkan tanda tangan digital pada kotak canvas yang disediakan (`ttd_pemohon`).
5. Sistem menyimpan data ke tabel `web_hukum_formulir_konsultasi` dengan status default:
   - `status_verifikasi = 0` (Menunggu Verifikasi)
   - `status_kasus_pengacara = 0` (Telaah Awal)
   - `status_jadwal_temu = 0` (Belum Ada Jadwal)
6. Klien menerima notifikasi bahwa permohonan berhasil terkirim dan menunggu verifikasi Sekretaris.

### B. Alur Verifikasi, Penugasan Advokat & Penjadwalan Temu
1. Sekretaris (Admin) membuka daftar permohonan konsultasi baru.
2. Sekretaris memeriksa kelengkapan data pemohon dan berkas bukti awal.
3. Jika data valid:
   - Sekretaris mengubah `status_verifikasi = 1` (Disetujui).
   - Menentukan klasifikasi jenis perkara (`klaterisasi_kasus`, e.g., Perdata, Pidana, TUN, dll.).
   - Menugaskan Advokat yang menangani perkara (`id_user_pengacara`).
   - Menetapkan tanggal, waktu, dan catatan janji temu (`tgl_waktu_jadwal_temu`, `catatan_jadwal_temu`) serta mengubah `status_jadwal_temu = 2` (Terjadwal).
4. Jika data tidak valid:
   - Sekretaris mengubah `status_verifikasi = 2` (Ditolak) disertai catatan alasan penolakan.

### C. Alur Konsultasi Interaktif (Fitur Chat)
1. Klien membuka ruang pesan (Chat) pada sistem web.
2. Pesan dikirimkan dan tersimpan di tabel `web_hukum_chat`.
3. Sekretaris menerima notifikasi pesan masuk pada panel dashboard admin dan merespons pertanyaan klien secara real-time / semi-real-time.
4. Riwayat percakapan tersimpan rapi sebagai arsip konsultasi digital.

### D. Alur Kontrak Kesepakatan & Pemantauan Sidang
1. Jika hasil konsultasi menyepakati bahwa kantor hukum akan mendampingi klien di pengadilan:
   - Sekretaris mengunggah dokumen PDF kontrak pendampingan hukum resmi ke tabel `web_hukum_kontrak_kesepakatan`.
2. Sekretaris/Advokat secara berkala memperbarui tahapan persidangan:
   - `0`: Non-Litigasi / Mediasi
   - `1`: Pendaftaran Gugatan
   - `2`: Pembuktian
   - `3`: Putusan
   - `4`: Inkracht
3. Klien dapat mengunduh berkas kontrak dan memantau status jadwal sidang secara langsung dari akunnya.

### E. Alur Penagihan & Pembayaran Otomatis (Payment Gateway Integration)
1. Sekretaris/Sistem menetapkan nominal tagihan penanganan perkara hukum pada tabel `web_hukum_pembayaran`.
2. Sistem otomatis mengenerate `order_id` unik (contoh: `ORDER-HKM-YYYYMMDD-XXXX`).
3. Klien membuka menu **Pembayaran Perkara** pada dashboard klien dan melihat rincian biaya.
4. Klien mengklik tombol **"Bayar Sekarang"**:
   - Sistem memanggil API Payment Gateway (misal: **Midtrans Snap**) untuk mendapatkan `snap_token`.
   - Muncul popup pembayaran interaktif di layar (Snap Modal) dengan berbagai opsi kanal bayar:
     - **QRIS** (GoPay, OVO, Dana, ShopeePay, LinkAja, BCA QR, dll.)
     - **Virtual Account Bank** (BCA, Mandiri, BNI, BRI, Permata)
     - **Convenience Store** (Indomaret, Alfamart)
5. Klien menyelesaikan transaksi pembayaran melalui kanal yang dipilih.
6. **Mekanisme Webhook / HTTP Notification**:
   - Server Payment Gateway mengirimkan notifikasi data transaksi secara *asynchronous* ke endpoint controller sistem (`/pembayaran/notification`).
   - Sistem memverifikasi validitas *signature key* keamanan.
   - Jika transaksi berstatus `settlement` atau `capture`, sistem secara otomatis mengubah:
     - `status = 1` (Lunas / Settlement)
     - `status_transaksi = 'settlement'`
     - `waktu_transaksi = NOW()`
     - `metode_pembayaran` = kanal yang digunakan klien
7. Klien langsung menerima status **Lunas** secara otomatis tanpa perlu menunggu verifikasi manual dari sekretaris kantor, dan kwitansi/invoice digital langsung tersedia.

---

## 11. Rancangan Kasus Uji (Black Box Testing Matrix)
*(Sesuai Metode Pengujian pada Bab I dan Bab IV Skripsi)*

| ID Pengujian | Modul & Skenario Uji | Data Masukan (Input) | Hasil yang Diharapkan | Status Uji |
| :--- | :--- | :--- | :--- | :---: |
| **TC-AUTH-01** | Registrasi Klien baru dengan data valid | Form registrasi lengkap, username unik, password valid | Akun tersimpan ke `web_hukum_user` dengan `role_id=3`, redirect ke login | *Rencana* |
| **TC-AUTH-02** | Registrasi dengan username yang sudah terdaftar | Username yang telah dipakai sebelumnya | Sistem menolak dan menampilkan pesan error duplikasi username | *Rencana* |
| **TC-AUTH-03** | Login multi-role (Admin/Pengacara/Klien) | Username & password benar | Sistem memvalidasi hash password dan mengarahkan ke dashboard role masing-masing | *Rencana* |
| **TC-KONS-01** | Pengajuan formulir konsultasi lengkap | Data identitas, uraian perkara, upload bukti awal, goresan TTD digital | Data tersimpan di `web_hukum_formulir_konsultasi`, nomor permohonan terbit | *Rencana* |
| **TC-KONS-02** | Validasi input tanda tangan digital kosong | Form diisi lengkap tetapi canvas TTD dikosongkan | Sistem mencegah submit dan meminta pemohon menandatangani form | *Rencana* |
| **TC-ADM-01** | Verifikasi & penugasan pengacara oleh Sekretaris | Pilih pengacara dan klik verifikasi setuju | `status_verifikasi=1`, pengacara terpilih menerima penugasan di dashboardnya | *Rencana* |
| **TC-ADM-02** | Penjadwalan temu konsultasi | Input tanggal, jam temu, dan catatan ruangan/lokasi | Jadwal tersimpan, status jadwal temu berubah menjadi terjadwal | *Rencana* |
| **TC-CHAT-01** | Pengiriman pesan chat dari klien ke sekretaris | Input teks pesan di kolom chat | Pesan tersimpan di database dan muncul pada percakapan sekretaris | *Rencana* |
| **TC-PROG-01** | Pembaruan progres perkara oleh Sekretaris | Ubah status perkara dari *Dalam Progres* ke *Selesai* | Status kasus terupdate, tampilan progres di sisi klien otomatis berubah | *Rencana* |
| **TC-PGW-01** | Request token Snap Payment Gateway oleh Klien | Klik tombol 'Bayar Sekarang' pada tagihan aktif | Sistem berhasil merequest `snap_token` ke gateway dan menampilkan modal popup bayar | *Rencana* |
| **TC-PGW-02** | Pembayaran via QRIS / Virtual Account simulasi | Pilih channel bayar dan selesaikan di simulator sandbox | Transaksi sukses, webhook gateway mentrigger callback ke sistem | *Rencana* |
| **TC-PGW-03** | Penanganan Webhook Callback Otomatis | Payload JSON notifikasi sukses dari Payment Gateway | Sistem memverifikasi SHA512 signature, update `status=1` (Lunas), invoice terbit otomatis | *Rencana* |
| **TC-LAP-01** | Generate laporan rekapitulasi kasus otomatis | Pilih filter periode tanggal laporan | Sistem menampilkan dan mengekspor rekapitulasi kasus perkara dalam format tabel/PDF | *Rencana* |


---

## 12. Kamus Data Rinci (Data Dictionary)

### 1. Tabel: `web_hukum_user`
| Nama Kolom | Tipe Data | Panjang | Nullable | Default | Keterangan |
| :--- | :--- | :--- | :---: | :---: | :--- |
| `id_user` | INT | 11 | NO | AUTO_INC | **Primary Key**. ID unik akun pengguna. |
| `name` | VARCHAR | 128 | NO | - | Nama lengkap pengguna / advokat / admin. |
| `username` | VARCHAR | 128 | NO | - | Username untuk autentikasi login (unik). |
| `image` | VARCHAR | 128 | NO | 'default.jpg' | Nama file gambar foto profil. |
| `password` | VARCHAR | 256 | NO | - | Password terenkripsi menggunakan `password_hash()`. |
| `role_id` | INT | 11 | NO | - | Level hak akses (1: Sekretaris, 2: Pengacara, 3: Klien). |
| `is_active` | INT | 1 | NO | 1 | Status aktif akun (1: Aktif, 0: Nonaktif). |
| `date_created` | INT | 11 | NO | - | Unix timestamp waktu pendaftaran akun. |

### 2. Tabel: `web_hukum_formulir_konsultasi`
| Nama Kolom | Tipe Data | Panjang | Nullable | Default | Keterangan |
| :--- | :--- | :--- | :---: | :---: | :--- |
| `id_formulir` | INT | 11 | NO | AUTO_INC | **Primary Key**. ID unik permohonan konsultasi. |
| `id_user` | INT | 11 | NO | - | **Foreign Key** ke `web_hukum_user.id_user` (Klien). |
| `id_user_pengacara`| VARCHAR | 255 | YES | NULL | Pengacara/Advokat penanggung jawab perkara. |
| `no_permohonan` | VARCHAR | 255 | NO | - | Nomor registrasi resmi permohonan (misal: `REG-HKM-YYYYMMDD-XXXX`). |
| `nama` | VARCHAR | 255 | NO | - | Nama lengkap pemohon konsultasi. |
| `tempat_lahir` | VARCHAR | 255 | YES | NULL | Tempat lahir pemohon. |
| `tgl_lahir` | DATE | - | YES | NULL | Tanggal lahir pemohon. |
| `jenis_kelamin` | VARCHAR | 255 | YES | NULL | Jenis kelamin pemohon (Laki-laki / Perempuan). |
| `agama` | VARCHAR | 255 | YES | NULL | Agama pemohon. |
| `alamat` | TEXT | - | YES | NULL | Alamat tempat tinggal lengkap pemohon. |
| `no_hp` | VARCHAR | 255 | YES | NULL | Nomor kontak telepon / WhatsApp aktif pemohon. |
| `pekerjaan` | VARCHAR | 255 | YES | NULL | Pekerjaan / profesi pemohon. |
| `waktu_pelaksanaan`| DATE | - | YES | NULL | Tanggal pelaksanaan/kejadian perkara hukum. |
| `uraian_singkat_perkara` | TEXT | - | YES | NULL | Ringkasan kronologi masalah hukum yang dihadapi. |
| `ttd_pemohon` | VARCHAR | 255 | YES | NULL | Nama file gambar tanda tangan digital Klien (.png). |
| `ttd_konsultan` | VARCHAR | 255 | YES | NULL | Nama file gambar tanda tangan digital Konsultan (.png). |
| `bukti_awal` | VARCHAR | 255 | YES | NULL | Nama berkas dokumen/bukti perkara awal (.pdf/.jpg/.png). |
| `klaterisasi_kasus`| INT | 11 | YES | NULL | Kode klasifikasi perkara (Tipikor, Perdata, Pidana, TUN, dll.). |
| `status_verifikasi`| INT | 11 | NO | 0 | 0: Menunggu Verifikasi, 1: Disetujui, 2: Ditolak. |
| `status_kasus_pengacara` | INT | 11 | NO | 0 | 0: Masuk/Telaah, 1: Dalam Progres, 2: Selesai. |
| `status_jadwal_temu`| INT | 11 | NO | 0 | 0: Belum Ada, 1: Menunggu, 2: Disetujui/Terjadwal, 3: Selesai. |
| `tgl_waktu_jadwal_temu` | DATETIME | - | YES | NULL | Tanggal dan jam temu konsultasi tatap muka. |
| `catatan_jadwal_temu` | VARCHAR | 255 | YES | NULL | Catatan/lokasi konsultasi tatap muka. |
| `created_at` | DATE | - | NO | - | Tanggal pengajuan permohonan dibuat. |

### 3. Tabel: `web_hukum_kontrak_kesepakatan`
| Nama Kolom | Tipe Data | Panjang | Nullable | Default | Keterangan |
| :--- | :--- | :--- | :---: | :---: | :--- |
| `id_kontrak` | INT | 11 | NO | AUTO_INC | **Primary Key**. ID unik kontrak kesepakatan. |
| `id_formulir` | INT | 11 | NO | - | **Foreign Key** ke `web_hukum_formulir_konsultasi.id_formulir`. |
| `keterangan` | VARCHAR | 255 | YES | NULL | Keterangan atau catatan isi kontrak. |
| `berkas_kontrak` | VARCHAR | 255 | YES | NULL | Nama file dokumen kontrak kesepakatan (.pdf). |
| `status_sidang` | INT | 11 | NO | 0 | Tahapan sidang (0: Non-Litigasi, 1: Gugatan, 2: Pembuktian, 3: Putusan, 4: Inkracht). |
| `created_at` | DATE | - | NO | - | Tanggal dokumen kontrak diunggah/dibuat. |

### 4. Tabel: `web_hukum_pembayaran`
| Nama Kolom | Tipe Data | Panjang | Nullable | Default | Keterangan |
| :--- | :--- | :--- | :---: | :---: | :--- |
| `id_pembayaran` | INT | 11 | NO | AUTO_INC | **Primary Key**. ID unik data tagihan/transaksi. |
| `id_formulir` | INT | 11 | NO | - | **Foreign Key** ke `web_hukum_formulir_konsultasi.id_formulir`. |
| `order_id` | VARCHAR | 100 | NO | - | **Unique Key**. Nomor order referensi Payment Gateway. |
| `jumlah_tagihan` | DECIMAL | 12,2 | NO | 0.00 | Besaran nominal biaya penanganan jasa hukum (Rp). |
| `metode_pembayaran`| VARCHAR | 50 | YES | NULL | Kanal bayar (qris, bca_va, bri_va, gopay, mandiri_va). |
| `transaction_id` | VARCHAR | 255 | YES | NULL | ID referensi transaksi resmi dari Payment Gateway. |
| `snap_token` | VARCHAR | 255 | YES | NULL | Token popup transaksi Midtrans Snap. |
| `payment_url` | VARCHAR | 255 | YES | NULL | Link checkout pembayaran online. |
| `status` | INT | 1 | NO | 0 | 0: Pending, 1: Settlement/Lunas, 2: Expired, 3: Batal. |
| `status_transaksi`| VARCHAR | 50 | NO | 'pending' | Status gateway (pending, settlement, expire, cancel, deny). |
| `bukti_bayar` | VARCHAR | 255 | YES | NULL | File/URL struk bukti pembayaran. |
| `keterangan` | TEXT | - | YES | NULL | Catatan transaksi atau rincian biaya perkara. |
| `waktu_transaksi` | DATETIME | - | YES | NULL | Waktu pelunasan sukses dari webhook gateway. |
| `created_at` | DATETIME | - | NO | CURRENT_TIME| Waktu tagihan diterbitkan. |
| `updated_at` | DATETIME | - | YES | NULL | Waktu terakhir status diperbarui. |

### 5. Tabel: `web_hukum_chat`
| Nama Kolom | Tipe Data | Panjang | Nullable | Default | Keterangan |
| :--- | :--- | :--- | :---: | :---: | :--- |
| `id_chat` | INT | 11 | NO | AUTO_INC | **Primary Key**. ID unik pesan chat. |
| `id_pengirim` | INT | 11 | NO | - | **Foreign Key** ke `web_hukum_user.id_user` (Pengirim). |
| `id_penerima` | INT | 11 | NO | - | **Foreign Key** ke `web_hukum_user.id_user` (Penerima). |
| `id_formulir` | INT | 11 | YES | NULL | ID formulir perkara terkait (jika percakapan terikat perkara). |
| `pesan` | TEXT | - | NO | - | Isi teks pesan konsultasi. |
| `is_read` | INT | 1 | NO | 0 | Status keterbacaan (0: Belum Dibaca, 1: Sudah Dibaca). |
| `created_at` | DATETIME | - | NO | CURRENT_TIME| Timestamp pengiriman pesan. |

---

## 13. Aturan Validasi Input Sistem (Validation Rules)

1. **Form Registrasi Klien**:
   - `name`: `required|trim|min_length[3]`
   - `username`: `required|trim|is_unique[web_hukum_user.username]|alpha_numeric`
   - `password`: `required|trim|min_length[6]|matches[password_confirm]`
2. **Form Pengajuan Permohonan Konsultasi**:
   - `nama`: `required|trim`
   - `no_hp`: `required|numeric|min_length[10]|max_length[15]`
   - `alamat`: `required`
   - `uraian_singkat_perkara`: `required|min_length[20]`
   - `bukti_awal`: `upload|allowed_types[pdf,jpg,jpeg,png]|max_size[2048]` (Maks 2MB)
   - `ttd_pemohon`: `required` (Goresan canvas tidak boleh kosong)
3. **Form Upload Bukti Pembayaran**:
   - `bukti_bayar`: `upload|allowed_types[jpg,jpeg,png,pdf]|max_size[2048]` (Maks 2MB)
4. **Form Verifikasi & Disposisi (Sekretaris)**:
   - `klaterisasi_kasus`: `required`
   - `id_user_pengacara`: `required`

---

## 14. Komponen Antarmuka Kunci (UI Component Specs)

1. **Modul Tanda Tangan Digital (Canvas Signature Pad)**:
   - Menggunakan library JavaScript `signature_pad.js`.
   - Mengubah goresan kanvas menjadi format Base64 PNG.
   - Disimpan di direktori `uploads/ttd/` dengan format penamaan aman: `ttd_pemohon_<id_user>_<timestamp>.png`.
2. **Modul Fitur Chat Interaktif**:
   - Tampilan antarmuka *chat bubble* (Pesan Klien di sisi kanan, Pesan Sekretaris di sisi kiri).
   - Mekanisme pembaruan pesan via AJAX polling otomatis (interval 3-5 detik) sehingga pesan masuk tanpa refresh halaman.
3. **Modul Monitoring Progres Timeline (Stepper / Progress Tracker)**:
   - Komponen visual baris progres tahapan perkara:
     `Pengajuan Formulir ➔ Verifikasi Berkas ➔ Jadwal Temu Konsultasi ➔ Kontrak Kesepakatan ➔ Sidang Pengadilan ➔ Selesai`.
   - Memberikan transparansi penuh kepada Klien mengenai tahapan perkara yang sedang berjalan.
