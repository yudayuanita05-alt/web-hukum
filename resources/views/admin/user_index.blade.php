@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-navy mb-1"><i class="fa-solid fa-users-gear text-primary me-2"></i> Manajemen Akun Pengguna</h3>
            <p class="text-muted small mb-0">Kelola akun Sekretariat, Advokat/Pengacara, dan Klien terdaftar.</p>
        </div>
        <button class="btn btn-navy" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
            <i class="fa-solid fa-user-plus me-1"></i> Tambah Pengguna Baru
        </button>
    </div>

    <div class="card card-custom p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Peran / Role</th>
                        <th>Status</th>
                        <th>Terdaftar Sejak</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td>{{ $u->id_user }}</td>
                            <td class="fw-bold text-navy">
                                <i class="fa-solid fa-circle-user text-muted me-2"></i>{{ $u->name }}
                            </td>
                            <td><code>{{ $u->username }}</code></td>
                            <td>
                                @if($u->id_role == 1)
                                    <span class="badge bg-danger">Sekretaris (Admin)</span>
                                @elseif($u->id_role == 2)
                                    <span class="badge bg-primary">Pengacara / Advokat</span>
                                @else
                                    <span class="badge bg-secondary">Klien</span>
                                @endif
                            </td>
                            <td>
                                @if($u->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td>{{ date('d M Y', $u->date_created) }}</td>
                            <td class="text-center">
                                @if($u->id_user != Auth::user()->id_user)
                                    <form action="{{ route('admin.users.toggle', $u->id_user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $u->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" onclick="return confirm('Apakah Anda yakin ingin mengubah status akun ini?')">
                                            @if($u->is_active)
                                                <i class="fa-solid fa-user-slash me-1"></i> Nonaktifkan
                                            @else
                                                <i class="fa-solid fa-user-check me-1"></i> Aktifkan
                                            @endif
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-light text-muted border">Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah Pengguna -->
<div class="modal fade" id="modalTambahUser" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-navy text-white">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-user-plus me-2"></i> Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Peran / Hak Akses <span class="text-danger">*</span></label>
                        <select name="id_role" class="form-select" required>
                            @foreach($roles as $r)
                                <option value="{{ $r->id_role }}">{{ $r->nama_role }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap (Termasuk Gelar) <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Bpk. Advokat, S.H., M.H." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control" placeholder="username_login" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-navy">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

