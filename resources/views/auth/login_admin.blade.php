@extends('layouts.app')

@section('title', 'Login Administrator')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card card-custom p-4 p-md-5 shadow">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-navy">Login Administrator</h3>
                    <p class="text-muted small">Masukkan kredensial admin untuk mengakses panel</p>
                </div>
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-navy w-100 py-2 mb-3">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk sebagai Admin
                    </button>
                </form>
                <div class="text-center mt-3">
                    <small class="text-muted">Tidak memiliki akun? Hubungi Sekretaris.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
