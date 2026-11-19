@extends('layouts.app')

@section('title', 'Masuk Akun')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-custom p-4 p-md-5 shadow">
                <div class="text-center mb-3">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center p-3 mb-2" style="background: rgba(10, 37, 64, 0.08); color: var(--law-navy);">
                        <i class="fa-solid fa-scale-balanced fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-navy">Masuk Sistem</h3>
                    <p class="text-muted small">Pilih peran akun atau masukkan username dan password Anda</p>
                </div>

                <!-- Tab Pemilihan Peran Cepat -->
                <div class="d-flex justify-content-center gap-1 mb-4 p-1 bg-light rounded-3 border">
                    <a href="{{ route('login') }}" class="btn btn-sm btn-navy fw-semibold px-2 py-1">
                        Semua Akun
                    </a>
                    <a href="{{ route('login.pengacara') }}" class="btn btn-sm btn-outline-success px-2 py-1">
                        <i class="fa-solid fa-scale-balanced me-1"></i> Pengacara
                    </a>
                    <a href="{{ route('login.admin') }}" class="btn btn-sm btn-outline-primary px-2 py-1">
                        <i class="fa-solid fa-user-shield me-1"></i> Admin
                    </a>
                    <a href="{{ route('login.klien') }}" class="btn btn-sm btn-outline-warning text-dark px-2 py-1">
                        <i class="fa-solid fa-user me-1"></i> Klien
                    </a>
                </div>

                <form id="formLogin" action="{{ route('login.post', [], false) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                            <input type="text" name="username" id="inputUsername" class="form-control border-start-0" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                            <input type="password" name="password" id="inputPassword" class="form-control border-start-0" placeholder="Masukkan password" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                            <label class="form-check-label small" for="remember">Ingat Saya</label>
                        </div>
                    </div>

                    <button type="submit" id="btnSubmit" class="btn btn-navy w-100 py-2 mb-3 fw-bold">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk Sekarang
                    </button>
                </form>

                <!-- Quick Test Login Buttons for Demo (1-Click Login) -->
                <div class="p-3 bg-light rounded-3 mb-3 border">
                    <small class="fw-bold text-navy d-block mb-2 text-center">
                        <i class="fa-solid fa-bolt text-warning me-1"></i> Klik untuk Masuk Langsung (1-Klik):
                    </small>
                    
                    <!-- Admin & Pimpinan -->
                    <div class="mb-2">
                        <small class="text-muted d-block small fw-bold mb-1"><i class="fa-solid fa-user-shield me-1"></i> Administrator & Pimpinan:</small>
                        <div class="d-flex flex-wrap gap-1">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="quickLogin('admin', 'password123')">
                                🔑 Admin / Sekretariat
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-dark" onclick="quickLogin('pimpinan', 'password123')">
                                🏛️ Pimpinan Law Firm
                            </button>
                        </div>
                    </div>

                    <!-- Advokat / Pengacara -->
                    <div class="mb-2">
                        <small class="text-success d-block small fw-bold mb-1"><i class="fa-solid fa-scale-balanced me-1"></i> Tim Advokat / Pengacara:</small>
                        <div class="d-flex flex-wrap gap-1">
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="quickLogin('ahmad_handoko', 'password123')">
                                ⚖️ Ahmad Handoko
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="quickLogin('pengacara1', 'password123')">
                                ⚖️ Yopi Hendro
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="quickLogin('reza_pahlevi', 'password123')">
                                ⚖️ M. Reza Pahlevi
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="quickLogin('dian_safitri', 'password123')">
                                ⚖️ Dian Safitri
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="quickLogin('fajar_ramadhan', 'password123')">
                                ⚖️ Fajar Ramadhan
                            </button>
                        </div>
                    </div>

                    <!-- Klien / Pemohon -->
                    <div>
                        <small class="text-warning text-dark d-block small fw-bold mb-1"><i class="fa-solid fa-user me-1"></i> Akun Klien:</small>
                        <div class="d-flex flex-wrap gap-1">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="quickLogin('klien1', 'password123')">
                                👤 Firhan Danuarta
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="quickLogin('klien2', 'password123')">
                                👤 Budi Santoso
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="quickLogin('klien3', 'password123')">
                                👤 Siti Nurhaliza
                            </button>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <small class="text-muted">Belum punya akun Klien? <a href="{{ route('register') }}" class="text-decoration-none fw-bold text-primary">Daftar Akun Baru</a></small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function quickLogin(user, pass) {
    document.getElementById('inputUsername').value = user;
    document.getElementById('inputPassword').value = pass;
    
    // Tampilkan indikator loading dan submit
    var btn = document.getElementById('btnSubmit');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Sedang Masuk sebagai ' + user + '...';
    btn.disabled = true;
    
    document.getElementById('formLogin').submit();
}
</script>
@endsection
