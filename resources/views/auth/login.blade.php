@extends('layouts.app')

@section('title', 'Masuk Sistem - Ahmad Handoko Law Office')

@section('styles')
<style>
    .login-wrapper {
        min-height: calc(100vh - 160px);
        display: flex;
        align-items: center;
    }
    /* ===== Left Hero Panel ===== */
    .brand-hero-card {
        background: linear-gradient(135deg, #061727 0%, #0A2540 50%, #0D3B66 100%);
        border: 1px solid rgba(197, 168, 128, 0.25);
        border-radius: 1.25rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(10, 37, 64, 0.35);
    }
    .brand-hero-card::before {
        content: '';
        position: absolute;
        top: -100px; right: -100px;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(197,168,128,0.12) 0%, transparent 70%);
        border-radius: 50%;
    }
    .brand-hero-card::after {
        content: '';
        position: absolute;
        bottom: -80px; left: -80px;
        width: 250px; height: 250px;
        background: radial-gradient(circle, rgba(59,130,246,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    .brand-seal {
        width: 70px; height: 70px;
        background: rgba(197,168,128,0.15);
        border: 2px solid var(--law-gold);
        border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        color: var(--law-gold);
        font-size: 2rem;
        box-shadow: 0 0 30px rgba(197,168,128,0.2);
    }
    .hero-illustration {
        max-width: 320px;
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
        background: rgba(197,168,128,0.12);
        border-color: rgba(197,168,128,0.3);
        transform: translateX(4px);
    }
    .welcome-title {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.2;
        background: linear-gradient(135deg, #FFFFFF 0%, #C5A880 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .stat-badge {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        padding: 12px 16px;
        text-align: center;
        flex: 1;
        transition: transform 0.2s;
    }
    .stat-badge:hover { transform: translateY(-2px); }
    .stat-badge .stat-number {
        font-size: 1.5rem; font-weight: 800; color: var(--law-gold);
    }
    .stat-badge .stat-label {
        font-size: 0.7rem; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px;
    }

    /* ===== Right Login Card ===== */
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
        background: var(--law-navy); color: #FFF !important;
        box-shadow: 0 2px 8px rgba(10,37,64,0.2);
    }
    .role-nav-tab:not(.active):hover {
        background: rgba(10,37,64,0.06);
    }
    .quick-user-card {
        display: flex; align-items: center; gap: 10px;
        width: 100%; padding: 10px 12px; border-radius: 10px;
        border: 1px solid #E2E8F0; background: #FFF;
        cursor: pointer; transition: all 0.2s ease; text-align: left;
    }
    .quick-user-card:hover {
        border-color: var(--law-gold); background: #FFFBF5;
        box-shadow: 0 4px 12px rgba(197,168,128,0.15);
        transform: translateY(-2px);
    }
    .quick-user-avatar {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.72rem; font-weight: 800; flex-shrink: 0;
    }
    .input-custom-group .form-control {
        padding-left: 42px; border-radius: 10px;
        border: 1.5px solid #E2E8F0; height: 46px;
        font-size: 0.9rem;
    }
    .input-custom-group .form-control:focus {
        border-color: var(--law-gold);
        box-shadow: 0 0 0 3px rgba(197,168,128,0.15);
    }
    .input-icon {
        position: absolute; left: 14px; top: 50%;
        transform: translateY(-50%);
        color: #94A3B8; font-size: 0.9rem; z-index: 4;
    }
    .input-custom-group .form-control:focus + .input-icon {
        color: var(--law-gold);
    }
    .btn-login-submit {
        background: linear-gradient(135deg, #0A2540 0%, #153A60 100%);
        color: #FFF; font-weight: 700; border: none;
        border-radius: 10px; padding: 12px; font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(10,37,64,0.25);
    }
    .btn-login-submit:hover {
        background: linear-gradient(135deg, #153A60 0%, #1E4D7B 100%);
        color: #FFF; transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(10,37,64,0.35);
    }
    .text-navy { color: var(--law-navy); }
    @media (max-width: 991.98px) {
        .welcome-title { font-size: 1.5rem; }
    }
</style>
@endsection

@section('content')
<div class="login-wrapper py-4">
    <div class="container">
        <div class="row g-4 align-items-stretch justify-content-center">

            {{-- ===== KOLOM KIRI: Ilustrasi + Selamat Datang ===== --}}
            <div class="col-lg-6 d-none d-lg-flex">
                <div class="brand-hero-card p-4 p-xl-5 d-flex flex-column justify-content-between text-white w-100">
                    <div>
                        {{-- Header Badge --}}
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="brand-seal">
                                <i class="fa-solid fa-scale-balanced"></i>
                            </div>
                            <div>
                                <span class="badge px-3 py-1 text-uppercase fw-bold" style="background: rgba(197,168,128,0.2); color: var(--law-gold); border: 1px solid rgba(197,168,128,0.4); font-size: 0.7rem;">
                                    Advokat & Konsultan Hukum
                                </span>
                                <h5 class="fw-bold mb-0 text-white mt-1">AHMAD HANDOKO</h5>
                                <small style="color: var(--law-gold); font-weight: 600; letter-spacing: 1.5px; font-size: 0.72rem;">LAW OFFICE & PARTNERS</small>
                            </div>
                        </div>

                        {{-- Selamat Datang --}}
                        <h2 class="welcome-title mb-3">
                            Selamat Datang <br>di Portal Hukum Kami
                        </h2>
                        <p class="text-light small mb-4 lh-lg" style="opacity: 0.7;">
                            Platform digital resmi Kantor Hukum Ahmad Handoko untuk pendampingan perkara litigasi & non-litigasi, kalender agenda sidang, konsultasi online, dan transparansi berkas perkara hukum.
                        </p>

                        {{-- SVG Illustration --}}
                        <div class="hero-illustration mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 320" fill="none">
                                <!-- Background elements -->
                                <rect x="80" y="60" width="180" height="220" rx="12" fill="#1E3A5F" stroke="#C5A880" stroke-width="1.5" opacity="0.6"/>
                                <rect x="90" y="70" width="160" height="30" rx="6" fill="#C5A880" opacity="0.3"/>
                                <rect x="90" y="110" width="120" height="8" rx="4" fill="#94A3B8" opacity="0.4"/>
                                <rect x="90" y="126" width="140" height="8" rx="4" fill="#94A3B8" opacity="0.3"/>
                                <rect x="90" y="142" width="100" height="8" rx="4" fill="#94A3B8" opacity="0.25"/>
                                <rect x="90" y="170" width="160" height="40" rx="8" fill="#0D3B66" stroke="#3B82F6" stroke-width="1"/>
                                <rect x="90" y="220" width="70" height="24" rx="6" fill="#C5A880" opacity="0.5"/>
                                <rect x="170" y="220" width="70" height="24" rx="6" fill="#3B82F6" opacity="0.4"/>

                                <!-- Person 1 - Lawyer with briefcase -->
                                <circle cx="340" cy="110" r="28" fill="#C5A880" opacity="0.9"/>
                                <circle cx="340" cy="100" r="18" fill="#F8FAFC"/>
                                <circle cx="340" cy="96" r="10" fill="#1E3A5F"/>
                                <rect x="322" y="118" width="36" height="45" rx="8" fill="#0A2540"/>
                                <rect x="328" y="124" width="24" height="6" rx="2" fill="#C5A880" opacity="0.6"/>
                                <!-- Briefcase -->
                                <rect x="310" y="168" width="40" height="28" rx="5" fill="#C5A880" stroke="#8B6914" stroke-width="1.5"/>
                                <rect x="322" y="162" width="16" height="10" rx="3" fill="none" stroke="#8B6914" stroke-width="1.5"/>

                                <!-- Person 2 - Client -->
                                <circle cx="420" cy="140" r="24" fill="#3B82F6" opacity="0.8"/>
                                <circle cx="420" cy="132" r="14" fill="#F8FAFC"/>
                                <circle cx="420" cy="128" r="8" fill="#1E3A5F"/>
                                <rect x="406" y="150" width="28" height="38" rx="6" fill="#1E4D7B"/>
                                <!-- Document in hand -->
                                <rect x="438" y="155" width="22" height="30" rx="3" fill="#F8FAFC" stroke="#CBD5E1" stroke-width="1"/>
                                <rect x="442" y="161" width="14" height="3" rx="1" fill="#94A3B8"/>
                                <rect x="442" y="167" width="10" height="3" rx="1" fill="#94A3B8"/>
                                <rect x="442" y="173" width="14" height="3" rx="1" fill="#C5A880"/>

                                <!-- Scales of Justice icon -->
                                <line x1="370" y1="40" x2="370" y2="70" stroke="#C5A880" stroke-width="2"/>
                                <line x1="345" y1="45" x2="395" y2="45" stroke="#C5A880" stroke-width="2"/>
                                <path d="M345 45 L338 60 L352 60 Z" fill="#C5A880" opacity="0.6"/>
                                <path d="M395 45 L388 60 L402 60 Z" fill="#C5A880" opacity="0.6"/>
                                <circle cx="370" cy="38" r="5" fill="#C5A880"/>

                                <!-- Decorative dots -->
                                <circle cx="60" cy="80" r="4" fill="#C5A880" opacity="0.3"/>
                                <circle cx="50" cy="100" r="3" fill="#3B82F6" opacity="0.3"/>
                                <circle cx="70" cy="120" r="5" fill="#C5A880" opacity="0.2"/>
                                <circle cx="280" cy="50" r="4" fill="#3B82F6" opacity="0.4"/>
                                <circle cx="470" cy="100" r="3" fill="#C5A880" opacity="0.3"/>
                                <circle cx="460" cy="210" r="5" fill="#3B82F6" opacity="0.2"/>

                                <!-- Gavel -->
                                <rect x="300" y="230" width="45" height="12" rx="4" fill="#8B6914" transform="rotate(-25 322 236)"/>
                                <rect x="280" y="260" width="60" height="10" rx="3" fill="#C5A880" opacity="0.7"/>
                                <rect x="295" y="250" width="30" height="14" rx="3" fill="#8B6914"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Stats Row --}}
                    <div class="d-flex gap-3 mb-4">
                        <div class="stat-badge">
                            <div class="stat-number">50+</div>
                            <div class="stat-label">Kasus Ditangani</div>
                        </div>
                        <div class="stat-badge">
                            <div class="stat-number">5</div>
                            <div class="stat-label">Advokat Aktif</div>
                        </div>
                        <div class="stat-badge">
                            <div class="stat-number">98%</div>
                            <div class="stat-label">Klien Puas</div>
                        </div>
                    </div>

                    {{-- Trust Pills --}}
                    <div class="d-flex flex-column gap-2 mb-3">
                        <div class="trust-pill">
                            <i class="fa-solid fa-shield-halved text-warning"></i>
                            <span>Kerahasiaan Berkas Terjamin Sesuai Kode Etik Advokat</span>
                        </div>
                        <div class="trust-pill">
                            <i class="fa-solid fa-calendar-check text-info"></i>
                            <span>Agenda Sidang & Konsultasi Tersinkronisasi Realtime</span>
                        </div>
                        <div class="trust-pill">
                            <i class="fa-solid fa-qrcode text-success"></i>
                            <span>Pembayaran Honorarium via QRIS Terverifikasi</span>
                        </div>
                    </div>

                    {{-- Footer Quote --}}
                    <div class="pt-3 border-top border-white border-opacity-10">
                        <blockquote class="fst-italic small mb-1" style="color: var(--law-gold);">
                            "Fiat Justitia Ruat Caelum"
                        </blockquote>
                        <small class="text-white-50 d-block" style="font-size: 0.72rem;">
                            Keadilan harus ditegakkan walau langit runtuh &bull; Skripsi: Firhan Danuarta (NPM 22430035) UM Metro 2026
                        </small>
                    </div>
                </div>
            </div>

            {{-- ===== KOLOM KANAN: Form Login ===== --}}
            <div class="col-lg-6 col-md-9 col-12">
                <div class="login-card-main p-4 p-md-5 h-100 d-flex flex-column justify-content-between">
                    <div>
                        {{-- Header --}}
                        <div class="text-center text-lg-start mb-4">
                            <div class="d-inline-flex d-lg-none align-items-center justify-content-center p-3 mb-2 rounded-circle" style="background: rgba(10,37,64,0.08); color: var(--law-navy);">
                                <i class="fa-solid fa-scale-balanced fs-3"></i>
                            </div>
                            <h3 class="fw-bold text-navy mb-1">Masuk ke Akun Anda</h3>
                            <p class="text-muted small mb-0">Silakan pilih akun di bawah atau masukkan username & password Anda</p>
                        </div>

                        {{-- Tab Navigasi Peran --}}
                        <div class="d-flex flex-wrap gap-1 mb-4 p-1 bg-light rounded-3 border">
                            <a href="{{ route('login') }}" class="role-nav-tab active">
                                <i class="fa-solid fa-layer-group"></i> Semua Akun
                            </a>
                            <a href="{{ route('login.pengacara') }}" class="role-nav-tab text-success">
                                <i class="fa-solid fa-scale-balanced"></i> Advokat
                            </a>
                            <a href="{{ route('login.admin') }}" class="role-nav-tab text-primary">
                                <i class="fa-solid fa-user-shield"></i> Sekretaris
                            </a>
                            <a href="{{ route('login.klien') }}" class="role-nav-tab text-warning text-dark">
                                <i class="fa-solid fa-user"></i> Klien
                            </a>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger py-2 small mb-3">
                                <i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('error') }}
                            </div>
                        @endif

                        {{-- 1-Click Fast Login --}}
                        <div class="p-3 bg-light rounded-3 mb-4 border border-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="fw-bold text-navy">
                                    <i class="fa-solid fa-bolt text-warning me-1"></i> Masuk Cepat (1-Klik):
                                </small>
                                <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">Mode Demo</span>
                            </div>

                            {{-- Pengacara --}}
                            <small class="text-muted small fw-bold d-block mb-1" style="font-size: 0.72rem;">
                                <i class="fa-solid fa-scale-balanced text-success me-1"></i> TIM ADVOKAT:
                            </small>
                            <div class="row g-2 mb-2">
                                <div class="col-sm-6">
                                    <button type="button" class="quick-user-card" onclick="fastLogin('ahmad_handoko', 'password123', 'Ahmad Handoko, S.H., M.H.')">
                                        <div class="quick-user-avatar bg-success text-white">AH</div>
                                        <div class="overflow-hidden">
                                            <div class="fw-bold text-dark text-truncate" style="font-size: 0.78rem;">Ahmad Handoko, S.H.</div>
                                            <small class="text-success fw-semibold" style="font-size: 0.68rem;">Managing Partner</small>
                                        </div>
                                    </button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="quick-user-card" onclick="fastLogin('pengacara1', 'password123', 'Yopi Hendro, S.H., M.H.')">
                                        <div class="quick-user-avatar bg-success text-white">YH</div>
                                        <div class="overflow-hidden">
                                            <div class="fw-bold text-dark text-truncate" style="font-size: 0.78rem;">Yopi Hendro, S.H.</div>
                                            <small class="text-muted" style="font-size: 0.68rem;">Advokat Litigasi</small>
                                        </div>
                                    </button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="quick-user-card" onclick="fastLogin('reza_pahlevi', 'password123', 'M. Reza Pahlevi, S.H.')">
                                        <div class="quick-user-avatar bg-success text-white">RP</div>
                                        <div class="overflow-hidden">
                                            <div class="fw-bold text-dark text-truncate" style="font-size: 0.78rem;">M. Reza Pahlevi, S.H.</div>
                                            <small class="text-muted" style="font-size: 0.68rem;">Advokat Perdata</small>
                                        </div>
                                    </button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="quick-user-card" onclick="fastLogin('dian_safitri', 'password123', 'Dian Safitri, S.H., M.Kn.')">
                                        <div class="quick-user-avatar bg-success text-white">DS</div>
                                        <div class="overflow-hidden">
                                            <div class="fw-bold text-dark text-truncate" style="font-size: 0.78rem;">Dian Safitri, S.H.</div>
                                            <small class="text-muted" style="font-size: 0.68rem;">Konsultan Bisnis</small>
                                        </div>
                                    </button>
                                </div>
                            </div>

                            {{-- Admin & Klien --}}
                            <small class="text-muted small fw-bold d-block mb-1" style="font-size: 0.72rem;">
                                <i class="fa-solid fa-users text-primary me-1"></i> ADMIN & KLIEN:
                            </small>
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <button type="button" class="quick-user-card" onclick="fastLogin('admin', 'password123', 'Sekretaris Kantor')">
                                        <div class="quick-user-avatar bg-primary text-white">ADM</div>
                                        <div class="overflow-hidden">
                                            <div class="fw-bold text-dark text-truncate" style="font-size: 0.78rem;">Sekretaris Kantor</div>
                                            <small class="text-primary fw-semibold" style="font-size: 0.68rem;">Admin Sistem</small>
                                        </div>
                                    </button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="quick-user-card" onclick="fastLogin('klien1', 'password123', 'Firhan Danuarta')">
                                        <div class="quick-user-avatar bg-warning text-dark">FD</div>
                                        <div class="overflow-hidden">
                                            <div class="fw-bold text-dark text-truncate" style="font-size: 0.78rem;">Firhan Danuarta</div>
                                            <small class="text-warning text-dark fw-semibold" style="font-size: 0.68rem;">Pemohon Kasus</small>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div class="position-relative text-center my-3">
                            <hr class="text-muted opacity-25">
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 small text-muted" style="font-size: 0.78rem;">
                                atau login dengan username
                            </span>
                        </div>

                        {{-- Form Manual --}}
                        <form id="formLoginManual" action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-navy">Username</label>
                                <div class="position-relative input-custom-group">
                                    <i class="fa-solid fa-user input-icon"></i>
                                    <input type="text" name="username" id="inputUsername" class="form-control" placeholder="Contoh: admin / ahmad_handoko / klien1" value="{{ old('username') }}" required autofocus>
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
                                    <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted text-decoration-none pe-3" onclick="togglePassVisibility()" style="z-index: 5;">
                                        <i class="fa-regular fa-eye" id="togglePassIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" checked>
                                    <label class="form-check-label small text-muted" for="rememberMe">Ingat akun ini</label>
                                </div>
                            </div>

                            <button type="submit" id="btnSubmitLogin" class="btn btn-login-submit w-100">
                                <i class="fa-solid fa-arrow-right-to-bracket me-2"></i> Masuk Sekarang
                            </button>
                        </form>
                    </div>

                    {{-- Footer --}}
                    <div class="text-center pt-3 mt-3 border-top border-light">
                        <small class="text-muted">
                            Belum memiliki akun Klien?
                            <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: var(--law-navy);">
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
function fastLogin(username, password, name) {
    document.getElementById('inputUsername').value = username;
    document.getElementById('inputPassword').value = password;

    const btn = document.getElementById('btnSubmitLogin');
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-2"></i> Mengautentikasi ' + name + '...';
    btn.disabled = true;

    document.getElementById('formLoginManual').submit();
}

function togglePassVisibility() {
    const input = document.getElementById('inputPassword');
    const icon = document.getElementById('togglePassIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection
