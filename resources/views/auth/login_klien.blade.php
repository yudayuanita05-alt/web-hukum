@extends('layouts.app')

@section('title', 'Portal Klien - Ahmad Handoko Law Office')

@section('styles')
<style>
    .login-wrapper {
        min-height: calc(100vh - 160px);
        display: flex;
        align-items: center;
    }
    .brand-hero-card {
        background: linear-gradient(135deg, #1a0a00 0%, #3D1E00 50%, #0A2540 100%);
        border: 1px solid rgba(245, 158, 11, 0.25);
        border-radius: 1.25rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(61, 30, 0, 0.35);
    }
    .brand-hero-card::before {
        content: '';
        position: absolute;
        top: -100px; right: -100px;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(245,158,11,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    .brand-seal {
        width: 70px; height: 70px;
        background: rgba(245,158,11,0.15);
        border: 2px solid #F59E0B;
        border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        color: #F59E0B;
        font-size: 2rem;
        box-shadow: 0 0 30px rgba(245,158,11,0.2);
    }
    .welcome-title {
        font-size: 2rem; font-weight: 800; line-height: 1.2;
        background: linear-gradient(135deg, #FFFFFF 0%, #F59E0B 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .hero-illustration {
        max-width: 280px;
        margin: 0 auto;
        filter: drop-shadow(0 10px 20px rgba(0,0,0,0.3));
    }
    .trust-pill {
        background: rgba(255,255,255,0.06);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.1);
        padding: 10px 16px;
        border-radius: 50px;
        font-size: 0.8rem;
        color: #CBD5E1;
        display: inline-flex; align-items: center; gap: 10px;
        transition: all 0.3s ease;
    }
    .trust-pill:hover {
        background: rgba(245,158,11,0.12);
        border-color: rgba(245,158,11,0.3);
        transform: translateX(4px);
    }
    .login-card-main {
        background: #FFFFFF;
        border-radius: 1.25rem;
        border: 1px solid rgba(226,232,240,0.9);
        box-shadow: 0 20px 50px rgba(15,23,42,0.08);
    }
    .role-nav-tab {
        border-radius: 10px; padding: 6px 12px; font-size: 0.82rem;
        font-weight: 600; transition: all 0.2s ease;
        text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
    }
    .role-nav-tab.active {
        background: #92400E; color: #FFF !important;
        box-shadow: 0 2px 8px rgba(146,64,14,0.3);
    }
    .role-nav-tab:not(.active):hover {
        background: rgba(245,158,11,0.08);
    }
    .quick-user-card {
        display: flex; align-items: center; gap: 10px;
        width: 100%; padding: 12px 14px; border-radius: 10px;
        border: 1px solid #E2E8F0; background: #FFF;
        cursor: pointer; transition: all 0.2s ease; text-align: left;
    }
    .quick-user-card:hover {
        border-color: #F59E0B; background: #FFFBEB;
        box-shadow: 0 4px 12px rgba(245,158,11,0.15);
        transform: translateY(-2px);
    }
    .quick-user-avatar {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: 800; flex-shrink: 0;
    }
    .input-custom-group .form-control {
        padding-left: 42px; border-radius: 10px;
        border: 1.5px solid #E2E8F0; height: 46px; font-size: 0.9rem;
    }
    .input-custom-group .form-control:focus {
        border-color: #F59E0B;
        box-shadow: 0 0 0 3px rgba(245,158,11,0.15);
    }
    .input-icon {
        position: absolute; left: 14px; top: 50%;
        transform: translateY(-50%);
        color: #94A3B8; font-size: 0.9rem; z-index: 4;
    }
    .btn-login-submit {
        background: linear-gradient(135deg, #92400E 0%, #B45309 100%);
        color: #FFF; font-weight: 700; border: none;
        border-radius: 10px; padding: 12px; font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(146,64,14,0.25);
    }
    .btn-login-submit:hover {
        background: linear-gradient(135deg, #B45309 0%, #D97706 100%);
        color: #FFF; transform: translateY(-2px);
    }
    .text-navy { color: #0A2540; }
</style>
@endsection

@section('content')
<div class="login-wrapper py-4">
    <div class="container">
        <div class="row g-4 align-items-stretch justify-content-center">

            {{-- KOLOM KIRI: Ilustrasi Klien --}}
            <div class="col-lg-6 d-none d-lg-flex">
                <div class="brand-hero-card p-4 p-xl-5 d-flex flex-column justify-content-between text-white w-100">
                    <div>
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="brand-seal">
                                <i class="fa-solid fa-user-shield"></i>
                            </div>
                            <div>
                                <span class="badge px-3 py-1 text-uppercase fw-bold" style="background: rgba(245,158,11,0.2); color: #F59E0B; border: 1px solid rgba(245,158,11,0.4); font-size: 0.7rem;">
                                    Portal Klien & Pemohon
                                </span>
                                <h5 class="fw-bold mb-0 text-white mt-1">HANDOKO & PARTNERS</h5>
                                <small style="color: #F59E0B; font-weight: 600; letter-spacing: 1.5px; font-size: 0.72rem;">CLIENT ACCESS PORTAL</small>
                            </div>
                        </div>

                        <h2 class="welcome-title mb-3">
                            Selamat Datang,<br>Klien Kami
                        </h2>
                        <p class="text-light small mb-4 lh-lg" style="opacity: 0.7;">
                            Akses layanan konsultasi hukum, pantau progres perkara Anda secara realtime, lihat jadwal sidang, dan kelola tagihan pembayaran honorarium dengan mudah.
                        </p>

                        {{-- SVG Illustration --}}
                        <div class="hero-illustration mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 260" fill="none">
                                <!-- Shield -->
                                <path d="M200 30 L260 55 L260 130 C260 180 200 220 200 220 C200 220 140 180 140 130 L140 55 Z" fill="#3D1E00" stroke="#F59E0B" stroke-width="2" opacity="0.6"/>
                                <path d="M200 50 L245 70 L245 125 C245 165 200 200 200 200 C200 200 155 165 155 125 L155 70 Z" fill="#1a0a00" stroke="#F59E0B" stroke-width="1" opacity="0.4"/>
                                <!-- Checkmark in shield -->
                                <path d="M180 120 L195 135 L225 100" stroke="#F59E0B" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" fill="none"/>

                                <!-- Person left -->
                                <circle cx="80" cy="100" r="20" fill="#F59E0B" opacity="0.7"/>
                                <circle cx="80" cy="94" r="12" fill="#F8FAFC"/>
                                <circle cx="80" cy="90" r="7" fill="#3D1E00"/>
                                <rect x="66" y="112" width="28" height="35" rx="6" fill="#92400E"/>

                                <!-- Person right -->
                                <circle cx="320" cy="100" r="20" fill="#3B82F6" opacity="0.7"/>
                                <circle cx="320" cy="94" r="12" fill="#F8FAFC"/>
                                <circle cx="320" cy="90" r="7" fill="#1E3A5F"/>
                                <rect x="306" y="112" width="28" height="35" rx="6" fill="#1E4D7B"/>

                                <!-- Document lines -->
                                <rect x="90" y="160" width="80" height="8" rx="4" fill="#F59E0B" opacity="0.3"/>
                                <rect x="90" y="175" width="60" height="8" rx="4" fill="#94A3B8" opacity="0.25"/>
                                <rect x="230" y="160" width="80" height="8" rx="4" fill="#3B82F6" opacity="0.3"/>
                                <rect x="230" y="175" width="60" height="8" rx="4" fill="#94A3B8" opacity="0.25"/>

                                <!-- Dots -->
                                <circle cx="40" cy="60" r="4" fill="#F59E0B" opacity="0.3"/>
                                <circle cx="360" cy="60" r="4" fill="#3B82F6" opacity="0.3"/>
                                <circle cx="50" cy="200" r="5" fill="#F59E0B" opacity="0.2"/>
                                <circle cx="350" cy="200" r="5" fill="#3B82F6" opacity="0.2"/>
                            </svg>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-2 mb-3">
                        <div class="trust-pill">
                            <i class="fa-solid fa-eye text-warning"></i>
                            <span>Pantau Progres Perkara Anda Secara Realtime</span>
                        </div>
                        <div class="trust-pill">
                            <i class="fa-solid fa-credit-card text-info"></i>
                            <span>Pembayaran Honorarium Online via QRIS</span>
                        </div>
                        <div class="trust-pill">
                            <i class="fa-solid fa-comments text-success"></i>
                            <span>Konsultasi Online dengan Advokat Pilihan</span>
                        </div>
                    </div>

                    <div class="pt-3 border-top border-white border-opacity-10">
                        <small class="text-white-50" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-lock me-1"></i> Data Anda dilindungi & dienkripsi sesuai standar keamanan hukum Indonesia.
                        </small>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Form Login Klien --}}
            <div class="col-lg-6 col-md-9 col-12">
                <div class="login-card-main p-4 p-md-5 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="text-center text-lg-start mb-4">
                            <div class="d-inline-flex d-lg-none align-items-center justify-content-center p-3 mb-2 rounded-circle" style="background: rgba(245,158,11,0.1); color: #92400E;">
                                <i class="fa-solid fa-user fs-3"></i>
                            </div>
                            <span class="badge bg-warning text-dark px-3 py-1 mb-2">Portal Klien</span>
                            <h3 class="fw-bold text-navy mb-1">Masuk Akun Klien</h3>
                            <p class="text-muted small mb-0">Akses layanan konsultasi, pantau progres perkara, dan tagihan Anda</p>
                        </div>

                        {{-- Tab Navigasi --}}
                        <div class="d-flex flex-wrap gap-1 mb-4 p-1 bg-light rounded-3 border">
                            <a href="{{ route('login') }}" class="role-nav-tab">
                                <i class="fa-solid fa-layer-group"></i> Semua Akun
                            </a>
                            <a href="{{ route('login.pengacara') }}" class="role-nav-tab text-success">
                                <i class="fa-solid fa-scale-balanced"></i> Pengacara
                            </a>
                            <a href="{{ route('login.admin') }}" class="role-nav-tab text-primary">
                                <i class="fa-solid fa-user-shield"></i> Admin
                            </a>
                            <a href="{{ route('login.klien') }}" class="role-nav-tab active">
                                <i class="fa-solid fa-user"></i> Klien
                            </a>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger py-2 small mb-3">
                                <i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('error') }}
                            </div>
                        @endif

                        {{-- 1-Click Login Klien --}}
                        <div class="p-3 bg-light rounded-3 mb-4 border border-warning border-opacity-50">
                            <small class="fw-bold text-dark d-block mb-2">
                                <i class="fa-solid fa-bolt text-warning me-1"></i> Klik untuk Masuk Langsung (1-Klik):
                            </small>
                            <div class="d-grid gap-2">
                                <button type="button" class="quick-user-card" onclick="quickLogin('klien1', 'password123')">
                                    <div class="quick-user-avatar bg-warning text-dark">FD</div>
                                    <div class="overflow-hidden flex-grow-1">
                                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">Firhan Danuarta (klien1)</div>
                                        <small class="text-muted" style="font-size: 0.72rem;">Pemohon Kasus Aktif</small>
                                    </div>
                                    <span class="badge bg-warning text-dark">Aktif</span>
                                </button>
                                <button type="button" class="quick-user-card" onclick="quickLogin('klien2', 'password123')">
                                    <div class="quick-user-avatar bg-secondary text-white">BS</div>
                                    <div class="overflow-hidden flex-grow-1">
                                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">Budi Santoso (klien2)</div>
                                        <small class="text-muted" style="font-size: 0.72rem;">Kasus Selesai</small>
                                    </div>
                                    <span class="badge bg-secondary">Selesai</span>
                                </button>
                                <button type="button" class="quick-user-card" onclick="quickLogin('klien3', 'password123')">
                                    <div class="quick-user-avatar bg-info text-white">SN</div>
                                    <div class="overflow-hidden flex-grow-1">
                                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">Siti Nurhaliza (klien3)</div>
                                        <small class="text-muted" style="font-size: 0.72rem;">Perkara Waris</small>
                                    </div>
                                    <span class="badge bg-info text-dark">Proses</span>
                                </button>
                            </div>
                        </div>

                        <div class="position-relative text-center my-3">
                            <hr class="text-muted opacity-25">
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 small text-muted">atau masuk manual</span>
                        </div>

                        <form id="formLoginKlien" action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-navy">Username Klien</label>
                                <div class="position-relative input-custom-group">
                                    <i class="fa-solid fa-user input-icon"></i>
                                    <input type="text" name="username" id="inputUsername" class="form-control" placeholder="Masukkan username klien" required autofocus>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label small fw-bold text-navy mb-0">Password</label>
                                    <span class="small text-muted" style="font-size: 0.75rem;">Default: <code>password123</code></span>
                                </div>
                                <div class="position-relative input-custom-group mt-1">
                                    <i class="fa-solid fa-lock input-icon"></i>
                                    <input type="password" name="password" id="inputPassword" class="form-control pe-5" placeholder="Masukkan password" required>
                                    <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted text-decoration-none pe-3" onclick="togglePass()" style="z-index: 5;">
                                        <i class="fa-regular fa-eye" id="togglePassIcon"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" id="btnSubmitKlien" class="btn btn-login-submit w-100">
                                <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk ke Portal Klien
                            </button>
                        </form>
                    </div>

                    <div class="text-center pt-3 mt-3 border-top border-light">
                        <small class="text-muted">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: #92400E;">
                                Daftar Akun Baru <i class="fa-solid fa-arrow-right small ms-1"></i>
                            </a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function quickLogin(user, pass) {
    document.getElementById('inputUsername').value = user;
    document.getElementById('inputPassword').value = pass;
    var btn = document.getElementById('btnSubmitKlien');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Masuk sebagai ' + user + '...';
    btn.disabled = true;
    document.getElementById('formLoginKlien').submit();
}
function togglePass() {
    const input = document.getElementById('inputPassword');
    const icon = document.getElementById('togglePassIcon');
    if (input.type === 'password') { input.type = 'text'; icon.classList.replace('fa-eye','fa-eye-slash'); }
    else { input.type = 'password'; icon.classList.replace('fa-eye-slash','fa-eye'); }
}
</script>
@endsection
