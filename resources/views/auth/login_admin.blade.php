@extends('layouts.app')

@section('title', 'Login Administrator - Ahmad Handoko Law Office')

@section('styles')
<style>
    .login-wrapper {
        min-height: calc(100vh - 160px);
        display: flex;
        align-items: center;
    }
    .brand-hero-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #0A2540 100%);
        border: 1px solid rgba(59, 130, 246, 0.3);
        border-radius: 1.25rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.25);
    }
    .brand-seal {
        width: 80px;
        height: 80px;
        background: rgba(59, 130, 246, 0.15);
        border: 2px solid #3B82F6;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #60A5FA;
        font-size: 2.2rem;
        box-shadow: 0 0 25px rgba(59, 130, 246, 0.25);
    }
    .login-card-main {
        background: #FFFFFF;
        border-radius: 1.25rem;
        border: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
    }
    .role-nav-tab {
        border-radius: 10px;
        padding: 6px 12px;
        font-size: 0.82rem;
        font-weight: 600;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .role-nav-tab.active {
        background: #0d6efd;
        color: #FFFFFF !important;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.25);
    }
    .btn-login-submit-admin {
        background: linear-gradient(135deg, #0d6efd 0%, #0A2540 100%);
        color: #FFFFFF;
        border: none;
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 700;
        transition: all 0.25s ease;
    }
    .btn-login-submit-admin:hover {
        background: linear-gradient(135deg, #0b5ed7 0%, #061727 100%);
        color: #FFFFFF;
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<div class="login-wrapper py-5">
    <div class="container">
        <div class="row g-4 align-items-stretch justify-content-center">
            
            <!-- Hero Side -->
            <div class="col-lg-6 d-none d-lg-flex">
                <div class="brand-hero-card p-5 d-flex flex-column justify-content-between text-white w-100">
                    <div>
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="brand-seal">
                                <i class="fa-solid fa-user-shield"></i>
                            </div>
                            <div>
                                <span class="badge bg-primary bg-opacity-25 text-primary border border-primary px-3 py-1 text-uppercase fw-bold mb-1">
                                    Sekretariat & Operasional
                                </span>
                                <h4 class="fw-bold mb-0 text-white">AHMAD HANDOKO</h4>
                                <small style="color: #93C5FD; font-weight: 600; letter-spacing: 1px;">KANTOR HUKUM & ADVOKAT</small>
                            </div>
                        </div>

                        <h2 class="fw-extrabold display-6 mb-3 text-white">
                            Panel Administrator <br>
                            <span style="color: #93C5FD;">& Verifikasi Berkas</span>
                        </h2>
                        <p class="text-light opacity-75 small mb-4 lh-lg">
                            Pusat kendali operasional sekretariat kantor hukum: verifikasi berkas perkara baru, disposisi penugasan advokat, kalender agenda sidang & jadwal temu, validasi tagihan honorarium serta rekapitulasi SHU kantor.
                        </p>

                        <div class="d-flex flex-column gap-2">
                            <div class="p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                                <h6 class="fw-bold text-white mb-1"><i class="fa-solid fa-calendar-check text-info me-2"></i> Manajemen Kalender & Reschedule Cepat</h6>
                                <p class="small text-white-50 mb-0">Kontrol penuh jadwal temu klien, ruang mediasi, serta pembaruan waktu persidangan pengacara.</p>
                            </div>
                            <div class="p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                                <h6 class="fw-bold text-white mb-1"><i class="fa-solid fa-file-invoice-dollar text-warning me-2"></i> Integrasi Pembayaran QRIS & SHU</h6>
                                <p class="small text-white-50 mb-0">Rekapitulasi otomatis honorarium advokat dan alokasi SHU kantor secara akurat.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-top border-white border-opacity-10">
                        <small class="text-white-50 d-block" style="font-size: 0.75rem;">
                            Sistem Informasi Layanan & Konsultasi Hukum &bull; Firhan Danuarta (NPM 22430035) 2026
                        </small>
                    </div>
                </div>
            </div>

            <!-- Login Form Side -->
            <div class="col-lg-6 col-md-9 col-12">
                <div class="login-card-main p-4 p-md-5 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="text-center text-lg-start mb-4">
                            <span class="badge bg-primary px-3 py-1 font-monospace mb-2">Portal Sekretariat</span>
                            <h3 class="fw-bold text-navy mb-1">Login Administrator</h3>
                            <p class="text-muted small mb-0">Masukkan akun admin atau gunakan tombol 1-klik untuk masuk cepat</p>
                        </div>

                        <!-- Tab Peran -->
                        <div class="d-flex flex-wrap gap-1 mb-4 p-1 bg-light rounded-3 border">
                            <a href="{{ route('login') }}" class="role-nav-tab text-secondary">
                                <i class="fa-solid fa-layer-group"></i> Semua Akun
                            </a>
                            <a href="{{ route('login.pengacara') }}" class="role-nav-tab text-success">
                                <i class="fa-solid fa-scale-balanced"></i> Advokat
                            </a>
                            <a href="{{ route('login.admin') }}" class="role-nav-tab active">
                                <i class="fa-solid fa-user-shield"></i> Sekretaris
                            </a>
                            <a href="{{ route('login.klien') }}" class="role-nav-tab text-warning text-dark">
                                <i class="fa-solid fa-user"></i> Klien
                            </a>
                        </div>

                        <!-- 1-Click Fast Login Card -->
                        <div class="p-3 bg-light rounded-3 mb-4 border border-primary border-opacity-25">
                            <small class="fw-bold text-primary d-block mb-2 text-center">
                                <i class="fa-solid fa-bolt text-warning me-1"></i> Klik untuk Masuk Langsung (1-Klik):
                            </small>
                            <button type="button" class="btn btn-outline-primary w-100 py-3 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm" onclick="fastLoginAdmin('admin', 'password123')">
                                <i class="fa-solid fa-key fs-5"></i>
                                <span>Masuk sebagai <strong>Admin / Sekretaris (admin)</strong></span>
                            </button>
                        </div>

                        <!-- Manual Form -->
                        <div class="position-relative text-center my-3">
                            <hr class="text-muted opacity-25">
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 small text-muted">atau masuk manual</span>
                        </div>

                        <form id="formLoginAdmin" action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-navy">Username Admin</label>
                                <input type="text" name="username" id="inputUsernameAdmin" class="form-control" placeholder="admin" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-navy">Password</label>
                                <input type="password" name="password" id="inputPasswordAdmin" class="form-control" placeholder="Masukkan password" required>
                            </div>
                            <button type="submit" id="btnSubmitAdmin" class="btn btn-login-submit-admin w-100">
                                <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk sebagai Administrator
                            </button>
                        </form>
                    </div>

                    <div class="text-center pt-3 mt-3 border-top border-light">
                        <small class="text-muted">
                            Bukan admin? <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Pilih Peran Lain</a>
                        </small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function fastLoginAdmin(username, password) {
    document.getElementById('inputUsernameAdmin').value = username;
    document.getElementById('inputPasswordAdmin').value = password;
    
    const btn = document.getElementById('btnSubmitAdmin');
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-2"></i> Masuk sebagai Admin...';
    btn.disabled = true;
    
    document.getElementById('formLoginAdmin').submit();
}
</script>
@endsection
