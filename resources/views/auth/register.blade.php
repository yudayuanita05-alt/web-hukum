@extends('layouts.app')

@section('title', 'Pendaftaran Akun Klien')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-custom p-4 p-md-5 shadow">
                <div class="text-center mb-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center p-3 mb-2" style="background: rgba(197, 168, 128, 0.2); color: var(--law-gold);">
                        <i class="fa-solid fa-user-plus fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-navy">Registrasi Klien Baru</h3>
                    <p class="text-muted small">Daftarkan akun untuk mengajukan konsultasi & memantau perkara hukum Anda</p>
                </div>

                <form action="{{ route('register.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap Sesuai KTP <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control" placeholder="Gunakan huruf & angka tanpa spasi (misal: budi_s)" value="{{ old('username') }}" required>
                        <small class="text-muted">Username akan digunakan untuk masuk ke portal sistem.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                        </div>
                    </div>

                    <div class="mb-4 form-check">
                        <input class="form-check-input" type="checkbox" id="terms" required checked>
                        <label class="form-check-label small text-muted" for="terms">
                            Saya menyetujui ketentuan layanan dan kebijakan kerahasiaan data kantor hukum.
                        </label>
                    </div>

                    <button type="submit" class="btn btn-navy w-100 py-2 mb-3">
                        <i class="fa-solid fa-check-circle me-2"></i> Daftar Akun Klien
                    </button>
                </form>

                <div class="text-center">
                    <small class="text-muted">Sudah memiliki akun? <a href="{{ route('login') }}" class="text-decoration-none fw-bold text-primary">Masuk di sini</a></small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

