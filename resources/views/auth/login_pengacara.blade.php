@extends('layouts.app')

@section('title', 'Login Pengacara')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card card-custom p-4 p-md-5 shadow">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-navy">Login Pengacara</h3>
                    <p class="text-muted small">Masukkan kredensial pengacara untuk mengakses panel</p>
                </div>
                <form action="{{ route('login.post', [], false) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username Advokat</label>
                        <input type="text" name="username" id="inputUsername" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" id="inputPassword" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-navy w-100 py-2 mb-3">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk sebagai Pengacara
                    </button>
                </form>

                <!-- Quick Test Login Buttons -->
                <div class="p-3 bg-light rounded-3 mb-3 border">
                    <small class="fw-bold text-muted d-block mb-2 text-center"><i class="fa-solid fa-key me-1"></i> Pilih Advokat untuk Login Cepat:</small>
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

                <div class="text-center mt-3">
                    <small class="text-muted">Tidak memiliki akun? Hubungi Sekretaris.</small>
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
