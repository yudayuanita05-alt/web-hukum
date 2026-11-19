@extends('layouts.app')

@section('title', 'Login Administrator / Sekretaris')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-custom p-4 p-md-5 shadow">
                <div class="text-center mb-3">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center p-3 mb-2" style="background: rgba(13, 110, 253, 0.12); color: #0d6efd;">
                        <i class="fa-solid fa-user-shield fs-2"></i>
                    </div>
                    <span class="badge bg-primary px-3 py-1 mb-2 font-monospace">Portal Sekretariat</span>
                    <h3 class="fw-bold text-navy">Login Administrator</h3>
                    <p class="text-muted small">Panel verifikasi berkas perkara, kalender jadwal, dan tagihan</p>
                </div>

                <!-- Tab Pemilihan Peran Cepat -->
                <div class="d-flex justify-content-center gap-1 mb-4 p-1 bg-light rounded-3 border">
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary px-2 py-1">
                        Semua Akun
                    </a>
                    <a href="{{ route('login.pengacara') }}" class="btn btn-sm btn-outline-success px-2 py-1">
                        <i class="fa-solid fa-scale-balanced me-1"></i> Pengacara
                    </a>
                    <a href="{{ route('login.admin') }}" class="btn btn-sm btn-primary fw-semibold px-2 py-1">
                        <i class="fa-solid fa-user-shield me-1"></i> Admin
                    </a>
                    <a href="{{ route('login.klien') }}" class="btn btn-sm btn-outline-warning text-dark px-2 py-1">
                        <i class="fa-solid fa-user me-1"></i> Klien
                    </a>
                </div>

                <!-- 1-Click Login Button -->
                <div class="p-3 bg-light rounded-3 mb-4 border border-primary border-opacity-25">
                    <small class="fw-bold text-primary d-block mb-2 text-center">
                        <i class="fa-solid fa-bolt text-warning me-1"></i> Klik untuk Masuk Langsung (1-Klik):
                    </small>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary py-2 px-3 fw-bold" onclick="quickLogin('admin', 'password123')">
                            🔑 <strong>Masuk sebagai Admin / Sekretaris (admin)</strong>
                        </button>
                    </div>
                </div>

                <div class="position-relative text-center my-3">
                    <hr class="text-muted">
                    <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 small text-muted">atau masuk manual</span>
                </div>

                <form id="formLoginAdmin" action="{{ route('login.post', [], false) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username Admin</label>
                        <input type="text" name="username" id="inputUsername" class="form-control" placeholder="Contoh: admin" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Masukkan password" required>
                    </div>
                    <button type="submit" id="btnSubmitAdmin" class="btn btn-navy w-100 py-2 mb-3 fw-bold">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk sebagai Admin
                    </button>
                </form>

                <div class="text-center mt-2">
                    <small class="text-muted">Bukan akun admin? <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Pilih Peran Lain</a></small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function quickLogin(user, pass) {
    document.getElementById('inputUsername').value = user;
    document.getElementById('inputPassword').value = pass;
    
    var btn = document.getElementById('btnSubmitAdmin');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Masuk sebagai ' + user + '...';
    btn.disabled = true;
    
    document.getElementById('formLoginAdmin').submit();
}
</script>
@endsection
