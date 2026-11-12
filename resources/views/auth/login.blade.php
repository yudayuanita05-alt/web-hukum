@extends('layouts.app')

@section('title', 'Masuk Akun')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card card-custom p-4 p-md-5 shadow">
                <div class="text-center mb-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center p-3 mb-2" style="background: rgba(10, 37, 64, 0.08); color: var(--law-navy);">
                        <i class="fa-solid fa-scale-balanced fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-navy">Masuk Sistem</h3>
                    <p class="text-muted small">Silakan masuk menggunakan akun terdaftar Anda</p>
                </div>

                <form action="{{ route('login.post') }}" method="POST">
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
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label small" for="remember">Ingat Saya</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-navy w-100 py-2 mb-3">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk Sekarang
                    </button>
                </form>

                <!-- Quick Test Login Buttons for Demo -->
                <div class="p-3 bg-light rounded-3 mb-3 border">
                    <small class="fw-bold text-muted d-block mb-2 text-center"><i class="fa-solid fa-key me-1"></i> Akun Uji Coba Cepat (Klik untuk Isi):</small>
                    <div class="d-flex flex-wrap gap-1 justify-content-center mb-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="quickFill('admin', 'password123')">
                            <i class="fa-solid fa-user-shield me-1"></i> Admin / Sekretaris
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-dark" onclick="quickFill('klien1', 'password123')">
                            <i class="fa-solid fa-user me-1"></i> Klien (Firhan)
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="quickFill('klien2', 'password123')">
                            <i class="fa-solid fa-user me-1"></i> Klien (Budi)
                        </button>
                    </div>
                    <small class="text-muted d-block text-center small mb-1 fw-semibold">Pilih Pengacara:</small>
                    <div class="d-flex flex-wrap gap-1 justify-content-center">
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="quickFill('ahmad_handoko', 'password123')">
                            ⚖️ Ahmad Handoko
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="quickFill('pengacara1', 'password123')">
                            ⚖️ Yopi Hendro
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="quickFill('reza_pahlevi', 'password123')">
                            ⚖️ M. Reza Pahlevi
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="quickFill('dian_safitri', 'password123')">
                            ⚖️ Dian Safitri
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="quickFill('fajar_ramadhan', 'password123')">
                            ⚖️ Fajar Ramadhan
                        </button>
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
function quickFill(user, pass) {
    document.getElementById('inputUsername').value = user;
    document.getElementById('inputPassword').value = pass;
}
</script>
@endsection

