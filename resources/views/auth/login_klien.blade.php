@extends('layouts.app')

@section('title', 'Login Portal Klien')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card card-custom p-4 p-md-5 shadow">
                <div class="text-center mb-4">
                    <span class="badge bg-warning text-dark px-3 py-1 mb-2 font-monospace">Portal Klien</span>
                    <h3 class="fw-bold text-navy mb-1">Masuk Akun Klien</h3>
                    <p class="text-muted small">Akses layanan konsultasi, pantau progres perkara, dan tagihan Anda</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger py-2 small mb-3">
                        <i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username Klien</label>
                        <input type="text" name="username" id="inputUsername" class="form-control" placeholder="Masukkan username" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Masukkan password" required>
                    </div>
                    <button type="submit" class="btn btn-navy w-100 py-2 mb-3 fw-bold">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk ke Portal Klien
                    </button>
                </form>

                <!-- Quick Test Login Buttons -->
                <div class="p-3 bg-light rounded-3 mb-3 border">
                    <small class="fw-bold text-muted d-block mb-2 text-center">
                        <i class="fa-solid fa-user-check me-1"></i> Akun Demo Klien (Klik untuk Mengisi):
                    </small>
                    <div class="d-flex flex-wrap gap-1 justify-content-center">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="quickFill('klien1', 'password123')">
                            👤 Firhan Danuarta
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="quickFill('klien2', 'password123')">
                            👤 Budi Santoso
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="quickFill('klien3', 'password123')">
                            👤 Siti Nurhaliza
                        </button>
                    </div>
                </div>

                <div class="text-center mt-2">
                    <small class="text-muted">Belum memiliki akun klien? 
                        <a href="{{ route('register') }}" class="text-decoration-none fw-bold text-navy">Daftar Akun Baru &rarr;</a>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function quickFill(username, password) {
        document.getElementById('inputUsername').value = username;
        document.getElementById('inputPassword').value = password;
    }
</script>
@endsection

