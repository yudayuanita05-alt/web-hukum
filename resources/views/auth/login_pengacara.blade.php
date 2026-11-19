@extends('layouts.app')

@section('title', 'Login Pengacara / Advokat')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-custom p-4 p-md-5 shadow">
                <div class="text-center mb-3">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center p-3 mb-2" style="background: rgba(25, 135, 84, 0.12); color: #198754;">
                        <i class="fa-solid fa-scale-balanced fs-2"></i>
                    </div>
                    <span class="badge bg-success px-3 py-1 mb-2 font-monospace">Portal Advokat</span>
                    <h3 class="fw-bold text-navy">Login Pengacara</h3>
                    <p class="text-muted small">Pilih nama advokat atau masukkan kredensial untuk masuk ke panel penanganan kasus</p>
                </div>

                <!-- Tab Pemilihan Peran Cepat -->
                <div class="d-flex justify-content-center gap-1 mb-4 p-1 bg-light rounded-3 border">
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary px-2 py-1">
                        Semua Akun
                    </a>
                    <a href="{{ route('login.pengacara') }}" class="btn btn-sm btn-success fw-semibold px-2 py-1">
                        <i class="fa-solid fa-scale-balanced me-1"></i> Pengacara
                    </a>
                    <a href="{{ route('login.admin') }}" class="btn btn-sm btn-outline-primary px-2 py-1">
                        <i class="fa-solid fa-user-shield me-1"></i> Admin
                    </a>
                    <a href="{{ route('login.klien') }}" class="btn btn-sm btn-outline-warning text-dark px-2 py-1">
                        <i class="fa-solid fa-user me-1"></i> Klien
                    </a>
                </div>

                <!-- Quick Test Login Buttons (1-Click Login Langsung Masuk) -->
                <div class="p-3 bg-light rounded-3 mb-4 border border-success border-opacity-25">
                    <small class="fw-bold text-success d-block mb-2 text-center">
                        <i class="fa-solid fa-bolt text-warning me-1"></i> Klik Advokat untuk Masuk Langsung (1-Klik):
                    </small>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-sm btn-outline-success text-start py-2 px-3 fw-semibold" onclick="quickLogin('ahmad_handoko', 'password123')">
                            ⚖️ <strong>Ahmad Handoko, S.H., M.H.</strong> <span class="badge bg-success float-end">Pimpinan/Senior</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success text-start py-2 px-3 fw-semibold" onclick="quickLogin('pengacara1', 'password123')">
                            ⚖️ <strong>Yopi Hendro, S.H., M.H.</strong> <span class="badge bg-secondary float-end">Advokat 1</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success text-start py-2 px-3 fw-semibold" onclick="quickLogin('reza_pahlevi', 'password123')">
                            ⚖️ <strong>M. Reza Pahlevi, S.H., M.H.</strong> <span class="badge bg-secondary float-end">Advokat 2</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success text-start py-2 px-3 fw-semibold" onclick="quickLogin('dian_safitri', 'password123')">
                            ⚖️ <strong>Dian Safitri, S.H., M.Kn.</strong> <span class="badge bg-secondary float-end">Advokat 3</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success text-start py-2 px-3 fw-semibold" onclick="quickLogin('fajar_ramadhan', 'password123')">
                            ⚖️ <strong>Fajar Ramadhan, S.H.</strong> <span class="badge bg-secondary float-end">Advokat 4</span>
                        </button>
                    </div>
                </div>

                <div class="position-relative text-center my-3">
                    <hr class="text-muted">
                    <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 small text-muted">atau masuk manual</span>
                </div>

                <form id="formLoginPengacara" action="{{ route('login.post', [], false) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username Advokat</label>
                        <input type="text" name="username" id="inputUsername" class="form-control" placeholder="Contoh: ahmad_handoko" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Masukkan password" required>
                    </div>
                    <button type="submit" id="btnSubmitPengacara" class="btn btn-navy w-100 py-2 mb-3 fw-bold">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk sebagai Pengacara
                    </button>
                </form>

                <div class="text-center mt-2">
                    <small class="text-muted">Bukan akun pengacara? <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Pilih Peran Lain</a></small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function quickLogin(user, pass) {
    document.getElementById('inputUsername').value = user;
    document.getElementById('inputPassword').value = pass;
    
    var btn = document.getElementById('btnSubmitPengacara');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Masuk sebagai ' + user + '...';
    btn.disabled = true;
    
    document.getElementById('formLoginPengacara').submit();
}
</script>
@endsection
