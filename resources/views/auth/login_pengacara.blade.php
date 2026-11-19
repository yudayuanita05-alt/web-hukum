@extends('layouts.app')

@section('title', 'Portal Advokat - Ahmad Handoko Law Office')

@section('styles')
<style>
    .login-wrapper {
        min-height: calc(100vh - 160px);
        display: flex;
        align-items: center;
    }
    .brand-hero-card {
        background: linear-gradient(135deg, #052e16 0%, #064e3b 50%, #0A2540 100%);
        border: 1px solid rgba(16, 185, 129, 0.3);
        border-radius: 1.25rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(5, 46, 22, 0.25);
    }
    .brand-seal {
        width: 80px;
        height: 80px;
        background: rgba(16, 185, 129, 0.15);
        border: 2px solid #10B981;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #10B981;
        font-size: 2.2rem;
        box-shadow: 0 0 25px rgba(16, 185, 129, 0.25);
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
        background: #198754;
        color: #FFFFFF !important;
        box-shadow: 0 4px 10px rgba(25, 135, 84, 0.25);
    }
    .btn-login-submit-advokat {
        background: linear-gradient(135deg, #198754 0%, #0A2540 100%);
        color: #FFFFFF;
        border: none;
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 700;
        transition: all 0.25s ease;
    }
    .btn-login-submit-advokat:hover {
        background: linear-gradient(135deg, #146c43 0%, #061727 100%);
        color: #FFFFFF;
        transform: translateY(-2px);
    }
    .advokat-select-card {
        background: #F8FAFC;
        border: 1.5px solid #E2E8F0;
        border-radius: 10px;
        padding: 10px 14px;
        transition: all 0.2s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        text-align: left;
    }
    .advokat-select-card:hover {
        background: #FFFFFF;
        border-color: #198754;
        transform: translateX(3px);
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.15);
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
                                <i class="fa-solid fa-scale-balanced"></i>
                            </div>
                            <div>
                                <span class="badge bg-success bg-opacity-25 text-success border border-success px-3 py-1 text-uppercase fw-bold mb-1">
                                    Portal Advokat & Litigasi
                                </span>
                                <h4 class="fw-bold mb-0 text-white">AHMAD HANDOKO</h4>
                                <small style="color: #6EE7B7; font-weight: 600; letter-spacing: 1px;">KANTOR HUKUM & ADVOKAT</small>
                            </div>
                        </div>

                        <h2 class="fw-extrabold display-6 mb-3 text-white">
                            Panel Advokat <br>
                            <span style="color: #6EE7B7;">Penanganan Kasus</span>
                        </h2>
                        <p class="text-light opacity-75 small mb-4 lh-lg">
                            Area kerja khusus tim advokat untuk penelaahan kronologi perkara klien, monitoring agenda persidangan, unggah bukti berita acara, penandatanganan surat kuasa elektronik, dan mediasi.
                        </p>

                        <div class="d-flex flex-column gap-2">
                            <div class="p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                                <h6 class="fw-bold text-white mb-1"><i class="fa-solid fa-calendar-days text-success me-2"></i> Sinkronisasi Kalender Sidang</h6>
                                <p class="small text-white-50 mb-0">Jadwal sidang pengadilan negeri, agama, dan mediasi terhubung otomatis ke kalender agenda kantor.</p>
                            </div>
                            <div class="p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                                <h6 class="fw-bold text-white mb-1"><i class="fa-solid fa-signature text-warning me-2"></i> Tanda Tangan Konsultan Digital</h6>
                                <p class="small text-white-50 mb-0">Legalitas berkas konsultasi tersertifikasi dengan tanda tangan digital resmi advokat penanggung jawab.</p>
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
                            <span class="badge bg-success px-3 py-1 font-monospace mb-2">Portal Advokat</span>
                            <h3 class="fw-bold text-navy mb-1">Masuk sebagai Advokat</h3>
                            <p class="text-muted small mb-0">Pilih nama advokat Anda di bawah untuk masuk otomatis 1-klik</p>
                        </div>

                        <!-- Tab Peran -->
                        <div class="d-flex flex-wrap gap-1 mb-4 p-1 bg-light rounded-3 border">
                            <a href="{{ route('login') }}" class="role-nav-tab text-secondary">
                                <i class="fa-solid fa-layer-group"></i> Semua Akun
                            </a>
                            <a href="{{ route('login.pengacara') }}" class="role-nav-tab active">
                                <i class="fa-solid fa-scale-balanced"></i> Advokat
                            </a>
                            <a href="{{ route('login.admin') }}" class="role-nav-tab text-primary">
                                <i class="fa-solid fa-user-shield"></i> Sekretaris
                            </a>
                            <a href="{{ route('login.klien') }}" class="role-nav-tab text-warning text-dark">
                                <i class="fa-solid fa-user"></i> Klien
                            </a>
                        </div>

                        <!-- 1-Click Fast Login List -->
                        <div class="mb-4">
                            <small class="fw-bold text-success d-block mb-2">
                                <i class="fa-solid fa-bolt text-warning me-1"></i> Pilih Advokat untuk Masuk Langsung (1-Klik):
                            </small>
                            <div class="d-flex flex-column gap-2">
                                <button type="button" class="advokat-select-card" onclick="fastLoginAdvokat('ahmad_handoko', 'password123', 'Ahmad Handoko, S.H., M.H.')">
                                    <div class="badge rounded-circle p-2 bg-success text-white fs-6">⚖️</div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-navy" style="font-size: 0.88rem;">Ahmad Handoko, S.H., M.H.</div>
                                        <small class="text-success fw-semibold" style="font-size: 0.72rem;">Managing Partner &bull; Perkara Khusus & TUN</small>
                                    </div>
                                    <span class="badge bg-success" style="font-size: 0.7rem;">Masuk &rarr;</span>
                                </button>
                                <button type="button" class="advokat-select-card" onclick="fastLoginAdvokat('pengacara1', 'password123', 'Yopi Hendro, S.H., M.H.')">
                                    <div class="badge rounded-circle p-2 bg-success text-white fs-6">⚖️</div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-navy" style="font-size: 0.88rem;">Yopi Hendro, S.H., M.H.</div>
                                        <small class="text-muted" style="font-size: 0.72rem;">Advokat Litigasi Pidana & Perbankan</small>
                                    </div>
                                    <span class="badge bg-secondary" style="font-size: 0.7rem;">Masuk &rarr;</span>
                                </button>
                                <button type="button" class="advokat-select-card" onclick="fastLoginAdvokat('reza_pahlevi', 'password123', 'M. Reza Pahlevi, S.H., M.H.')">
                                    <div class="badge rounded-circle p-2 bg-success text-white fs-6">⚖️</div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-navy" style="font-size: 0.88rem;">M. Reza Pahlevi, S.H., M.H.</div>
                                        <small class="text-muted" style="font-size: 0.72rem;">Spesialis Hukum Perdata & Sengketa Tanah</small>
                                    </div>
                                    <span class="badge bg-secondary" style="font-size: 0.7rem;">Masuk &rarr;</span>
                                </button>
                                <button type="button" class="advokat-select-card" onclick="fastLoginAdvokat('dian_safitri', 'password123', 'Dian Safitri, S.H., M.Kn.')">
                                    <div class="badge rounded-circle p-2 bg-success text-white fs-6">⚖️</div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-navy" style="font-size: 0.88rem;">Dian Safitri, S.H., M.Kn.</div>
                                        <small class="text-muted" style="font-size: 0.72rem;">Hukum Bisnis, Waris & Perjanjian Korporasi</small>
                                    </div>
                                    <span class="badge bg-secondary" style="font-size: 0.7rem;">Masuk &rarr;</span>
                                </button>
                                <button type="button" class="advokat-select-card" onclick="fastLoginAdvokat('fajar_ramadhan', 'password123', 'Fajar Ramadhan, S.H.')">
                                    <div class="badge rounded-circle p-2 bg-success text-white fs-6">⚖️</div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-navy" style="font-size: 0.88rem;">Fajar Ramadhan, S.H.</div>
                                        <small class="text-muted" style="font-size: 0.72rem;">Pendampingan Ketenagakerjaan & PHI</small>
                                    </div>
                                    <span class="badge bg-secondary" style="font-size: 0.7rem;">Masuk &rarr;</span>
                                </button>
                            </div>
                        </div>

                        <!-- Manual Form -->
                        <div class="position-relative text-center my-3">
                            <hr class="text-muted opacity-25">
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 small text-muted">atau masuk manual</span>
                        </div>

                        <form id="formLoginAdvokat" action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-navy">Username Advokat</label>
                                <input type="text" name="username" id="inputUsernameAdvokat" class="form-control" placeholder="Contoh: ahmad_handoko" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-navy">Password</label>
                                <input type="password" name="password" id="inputPasswordAdvokat" class="form-control" placeholder="Masukkan password" required>
                            </div>
                            <button type="submit" id="btnSubmitAdvokat" class="btn btn-login-submit-advokat w-100">
                                <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk ke Panel Advokat
                            </button>
                        </form>
                    </div>

                    <div class="text-center pt-3 mt-3 border-top border-light">
                        <small class="text-muted">
                            Bukan advokat? <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Pilih Peran Lain</a>
                        </small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function fastLoginAdvokat(username, password, name) {
    document.getElementById('inputUsernameAdvokat').value = username;
    document.getElementById('inputPasswordAdvokat').value = password;
    
    const btn = document.getElementById('btnSubmitAdvokat');
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-2"></i> Mengautentikasi ' + name + '...';
    btn.disabled = true;
    
    document.getElementById('formLoginAdvokat').submit();
}
</script>
@endsection
