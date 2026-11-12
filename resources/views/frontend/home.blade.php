@extends('layouts.app')

@section('title', 'Beranda - Handoko & Partners Law Firm')

@section('content')
<!-- Hero Section -->
<section class="py-5" style="background: linear-gradient(135deg, #0A2540 0%, #173B61 100%); color: #FFFFFF; border-radius: 0 0 40px 40px; margin-top: -1.5rem;">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-7 text-center text-lg-start mb-4 mb-lg-0">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3">
                    <i class="fa-solid fa-scale-balanced me-1"></i> Kantor Advokat & Konsultan Hukum Profesional
                </span>
                <h1 class="display-4 fw-extrabold mb-3" style="letter-spacing: -0.5px;">
                    Integritas, Keadilan, & <span style="color: var(--law-gold);">Kepastian Hukum</span>
                </h1>
                <p class="lead text-light mb-4" style="opacity: 0.9;">
                    Selamat datang di Kantor Hukum <strong>Handoko & Partners Law Firm</strong> (Handoko & Rekan). Kami beranggotakan para advokat berpengalaman yang siap memberikan pelayanan konsultasi dan pendampingan hukum secara digital, transparan, cepat, dan profesional.
                </p>
                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start">
                    <a href="{{ route('klien.konsultasi.create') }}" class="btn btn-gold btn-lg px-4 py-2">
                        <i class="fa-solid fa-pen-nib me-2"></i> Ajukan Konsultasi Online
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4 py-2">
                        <i class="fa-solid fa-user-lock me-2"></i> Portal Klien & Advokat
                    </a>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <div class="p-4 rounded-4 shadow-lg" style="background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.15);">
                    <div class="mb-3 text-warning">
                        <i class="fa-solid fa-landmark display-1"></i>
                    </div>
                    <h4 class="text-white fw-bold mb-2">Handoko & Partners Law Firm</h4>
                    <p class="small text-light mb-3">Kantor Advokat, Kurator & Konsultan Hukum untuk Masyarakat, Lembaga & Korporasi.</p>
                    <div class="d-flex justify-content-around text-center border-top pt-3 border-secondary">
                        <div>
                            <h4 class="fw-bold mb-0 text-warning">7+</h4>
                            <small class="text-light">Bidang Hukum</small>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0 text-warning">100%</h4>
                            <small class="text-light">Digital & Transparan</small>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0 text-warning">Payment</h4>
                            <small class="text-light">Gateway QRIS & VA</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Layanan Hukum / Klasterisasi Kasus -->
<section id="layanan" class="py-5">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="text-uppercase fw-bold text-muted small" style="letter-spacing: 2px;">Spesialisasi Kami</span>
            <h2 class="fw-bold text-navy mt-1">Bidang Layanan & Perkara Hukum</h2>
            <div class="mx-auto" style="width: 80px; height: 4px; background: var(--law-gold); border-radius: 2px;"></div>
        </div>

        <div class="row g-4">
            @foreach($klasterisasi as $k)
                <div class="col-md-6 col-lg-4">
                    <div class="card card-custom h-100 p-4 border-start border-4 border-primary">
                        <div class="d-flex align-items-center mb-3">
                            <div class="p-3 rounded-circle me-3" style="background: rgba(10, 37, 64, 0.08); color: var(--law-navy);">
                                <i class="fa-solid fa-gavel fs-4"></i>
                            </div>
                            <h5 class="fw-bold mb-0 text-navy">{{ $k->nama_klasterisasi }}</h5>
                        </div>
                        <p class="text-muted small mb-0">{{ $k->keterangan }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Tim Advokat & Pengacara Profesional -->
<section id="advokat" class="py-5 bg-light">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="text-uppercase fw-bold text-muted small" style="letter-spacing: 2px;">Tim Ahli Kami</span>
            <h2 class="fw-bold text-navy mt-1">Daftar Advokat & Konsultan Hukum</h2>
            <p class="text-muted small">Anda dapat memilih langsung advokat berpengalaman yang siap mendampingi perkara hukum Anda.</p>
            <div class="mx-auto" style="width: 80px; height: 4px; background: var(--law-gold); border-radius: 2px;"></div>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach($advokat as $a)
                <div class="col-md-6 col-lg-4">
                    <div class="card card-custom h-100 p-4 text-center shadow-sm border-0 position-relative">
                        <div class="position-absolute top-0 end-0 p-3">
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill small">
                                <i class="fa-solid fa-circle-check me-1"></i> Aktif Berpraktik
                            </span>
                        </div>
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #0A2540 0%, #1E3A8A 100%); color: #D4AF37;">
                            <i class="fa-solid fa-user-tie fs-2"></i>
                        </div>
                        <h5 class="fw-bold text-navy mb-1">{{ $a->name }}</h5>
                        <p class="text-muted small mb-3">Advokat & Konsultan Hukum</p>
                        <div class="p-2 rounded bg-light small text-muted mb-3">
                            <i class="fa-solid fa-scale-balanced text-warning me-1"></i> Penanganan Perkara Litigasi & Non-Litigasi
                        </div>
                        <div class="mt-auto">
                            <a href="{{ route('klien.konsultasi.create') }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill">
                                <i class="fa-solid fa-file-pen me-1"></i> Pilih & Ajukan Konsultasi
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Profil & Alur Layanan Digital -->
<section id="profil" class="py-5 bg-white border-top border-bottom">
    <div class="container py-3">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <span class="text-uppercase fw-bold text-muted small" style="letter-spacing: 2px;">Inovasi Pelayanan Hukum</span>
                <h2 class="fw-bold text-navy mt-1 mb-3">Konsultasi Hukum Kini Lebih Mudah & Cepat Tanpa Perlu Antre</h2>
                <p class="text-muted">
                    Melalui digitalisasi sistem informasi berbasis website ini, Klien tidak lagi harus datang secara langsung ke kantor untuk mencatat di buku besar manual. Seluruh alur perkara kini terstruktur dan terpantau secara transparan:
                </p>
                <div class="d-flex mb-3">
                    <div class="me-3 text-primary"><i class="fa-solid fa-circle-check fs-4"></i></div>
                    <div>
                        <h6 class="fw-bold mb-1">Pengajuan Konsultasi & TTD Digital</h6>
                        <p class="text-muted small mb-0">Isi formulir online dan bubuhkan tanda tangan digital langsung pada layar perangkat Anda.</p>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <div class="me-3 text-primary"><i class="fa-solid fa-comments fs-4"></i></div>
                    <div>
                        <h6 class="fw-bold mb-1">Fitur Live Chat Konsultasi</h6>
                        <p class="text-muted small mb-0">Komunikasi tanya jawab langsung dengan tim sekretariat dan advokat.</p>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <div class="me-3 text-primary"><i class="fa-solid fa-qrcode fs-4"></i></div>
                    <div>
                        <h6 class="fw-bold mb-1">Payment Gateway Otomatis</h6>
                        <p class="text-muted small mb-0">Pembayaran tagihan perkara secara instan menggunakan QRIS, Virtual Account Bank, dan E-Wallet.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-4 p-md-5 rounded-4 shadow" style="background: var(--law-navy); color: #FFFFFF;">
                    <h4 class="fw-bold text-warning mb-3"><i class="fa-solid fa-quote-left me-2"></i> Landasan Keadilan</h4>
                    <p class="fst-italic text-light mb-3" style="line-height: 1.8;">
                        "Wahai orang-orang yang beriman, jadilah kamu penegak keadilan dan saksi karena Allah, walaupun kesaksian itu memberatkan dirimu sendiri, ibu bapakmu, atau kerabatmu..."
                    </p>
                    <small class="text-warning fw-bold">— QS. An-Nisa (4): 135</small>
                    <hr class="border-secondary my-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fw-bold me-3" style="width: 50px; height: 50px;">
                            <i class="fa-solid fa-user-tie fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-white">Yopi Hendro, S.H., M.H.</h6>
                            <small class="text-light">Partner & Advokat Senior Handoko & Partners Law Firm</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kontak & Lokasi -->
<section id="kontak" class="py-5">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="text-uppercase fw-bold text-muted small" style="letter-spacing: 2px;">Hubungi Kami</span>
            <h2 class="fw-bold text-navy mt-1">Kantor & Pusat Layanan</h2>
            <div class="mx-auto" style="width: 80px; height: 4px; background: var(--law-gold); border-radius: 2px;"></div>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="card card-custom text-center p-4 h-100">
                    <div class="text-primary mb-3"><i class="fa-solid fa-location-dot fs-2"></i></div>
                    <h5 class="fw-bold text-navy">Alamat Kantor</h5>
                    <p class="text-muted small mb-0">Bandar Lampung, Provinsi Lampung, Indonesia</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom text-center p-4 h-100">
                    <div class="text-primary mb-3"><i class="fa-solid fa-globe fs-2"></i></div>
                    <h5 class="fw-bold text-navy">Website Resmi</h5>
                    <p class="text-muted small mb-0">handokopartners.law</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom text-center p-4 h-100">
                    <div class="text-primary mb-3"><i class="fa-solid fa-envelope fs-2"></i></div>
                    <h5 class="fw-bold text-navy">Konsultasi Online</h5>
                    <p class="text-muted small mb-0">Tersedia 24/7 melalui Formulir Permohonan & Chat Online</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

