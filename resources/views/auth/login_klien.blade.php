@extends('layouts.app')

@section('title', 'Login Portal Klien')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-custom p-4 p-md-5 shadow">
                <div class="text-center mb-3">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center p-3 mb-2" style="background: rgba(255, 193, 7, 0.15); color: #b78103;">
                        <i class="fa-solid fa-user fs-2"></i>
                    </div>
                    <span class="badge bg-warning text-dark px-3 py-1 mb-2 font-monospace">Portal Klien</span>
                    <h3 class="fw-bold text-navy mb-1">Masuk Akun Klien</h3>
                    <p class="text-muted small">Akses layanan konsultasi, pantau progres perkara, dan tagihan Anda</p>
                </div>

                <!-- Tab Pemilihan Peran Cepat -->
                <div class="d-flex justify-content-center gap-1 mb-4 p-1 bg-light rounded-3 border">
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary px-2 py-1">
                        Semua Akun
                    </a>
                    <a href="{{ route('login.pengacara') }}" class="btn btn-sm btn-outline-success px-2 py-1">
                        <i class="fa-solid fa-scale-balanced me-1"></i> Pengacara
                    </a>
                    <a href="{{ route('login.admin') }}" class="btn btn-sm btn-outline-primary px-2 py-1">
                        <i class="fa-solid fa-user-shield me-1"></i> Admin
                    </a>
                    <a href="{{ route('login.klien') }}" class="btn btn-sm btn-warning fw-semibold text-dark px-2 py-1">
                        <i class="fa-solid fa-user me-1"></i> Klien
                    </a>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger py-2 small mb-3">
                        <i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('error') }}
                    </div>
                @endif

                <!-- 1-Click Login Klien -->
                <div class="p-3 bg-light rounded-3 mb-4 border border-warning border-opacity-50">
                    <small class="fw-bold text-dark d-block mb-2 text-center">
                        <i class="fa-solid fa-bolt text-warning me-1"></i> Klik Klien untuk Masuk Langsung (1-Klik):
                    </small>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary text-start py-2 px-3 fw-semibold" onclick="quickLogin('klien1', 'password123')">
                            👤 <strong>Firhan Danuarta (klien1)</strong> <span class="badge bg-primary float-end">Pemohon Kasus 1</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary text-start py-2 px-3 fw-semibold" onclick="quickLogin('klien2', 'password123')">
                            👤 <strong>Budi Santoso (klien2)</strong> <span class="badge bg-secondary float-end">Kasus Selesai</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary text-start py-2 px-3 fw-semibold" onclick="quickLogin('klien3', 'password123')">
                            👤 <strong>Siti Nurhaliza (klien3)</strong> <span class="badge bg-secondary float-end">Perkara Waris</span>
                        </button>
                    </div>
                </div>

                <div class="position-relative text-center my-3">
                    <hr class="text-muted">
                    <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 small text-muted">atau masuk manual</span>
                </div>

                <form id="formLoginKlien" action="{{ route('login.post', [], false) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username Klien</label>
                        <input type="text" name="username" id="inputUsername" class="form-control" placeholder="Masukkan username klien" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Masukkan password" required>
                    </div>
                    <button type="submit" id="btnSubmitKlien" class="btn btn-navy w-100 py-2 mb-3 fw-bold">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk ke Portal Klien
                    </button>
                </form>

                <div class="text-center">
                    <small class="text-muted">Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none fw-bold text-primary">Daftar Akun Baru</a></small>
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
</script>
@endsection
