@extends('layouts.app')

@section('title', 'Manajemen Permohonan & Perkara')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-navy mb-1"><i class="fa-solid fa-folder-open text-primary me-2"></i> Data Permohonan Konsultasi & Perkara</h3>
            <p class="text-muted small mb-0">Kelola verifikasi, disposisi advokat, penjadwalan temu, dan progres perkara klien.</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card card-custom p-3 mb-4">
        <form method="GET" action="{{ route('admin.konsultasi.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama pemohon, no. registrasi, no. HP..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status_verifikasi" class="form-select form-select-sm">
                    <option value="">-- Status Verifikasi (Semua) --</option>
                    <option value="0" {{ request('status_verifikasi') === '0' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="1" {{ request('status_verifikasi') === '1' ? 'selected' : '' }}>Disetujui</option>
                    <option value="2" {{ request('status_verifikasi') === '2' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status_kasus" class="form-select form-select-sm">
                    <option value="">-- Progres Kasus (Semua) --</option>
                    <option value="0" {{ request('status_kasus') === '0' ? 'selected' : '' }}>Telaah Awal</option>
                    <option value="1" {{ request('status_kasus') === '1' ? 'selected' : '' }}>Dalam Progres</option>
                    <option value="2" {{ request('status_kasus') === '2' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-navy btn-sm w-100"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                <a href="{{ route('admin.konsultasi.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-rotate-left"></i></a>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="card card-custom p-4">
        @if($daftarKonsultasi->isEmpty())
            <div class="text-center py-5">
                <i class="fa-solid fa-folder-closed display-4 text-muted mb-3"></i>
                <h6 class="text-muted">Tidak ditemukan data permohonan yang sesuai kriteria.</h6>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No. Permohonan</th>
                            <th>Tanggal</th>
                            <th>Pemohon</th>
                            <th>Klaster Perkara</th>
                            <th>Advokat</th>
                            <th>Verifikasi</th>
                            <th>Progres</th>
                            <th>Jadwal Temu</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($daftarKonsultasi as $k)
                            <tr>
                                <td class="fw-bold text-navy">{{ $k->no_permohonan }}</td>
                                <td>{{ $k->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="fw-bold">{{ $k->nama }}</div>
                                    <small class="text-muted">{{ $k->no_hp }}</small>
                                </td>
                                <td>{{ $k->klasterisasi->nama_klasterisasi ?? 'Belum Ditentukan' }}</td>
                                <td>
                                    @if($k->pengacara)
                                        <i class="fa-solid fa-user-tie text-primary me-1"></i> {{ $k->pengacara->name }}
                                    @else
                                        <span class="badge bg-light text-muted border">Belum Ditugaskan</span>
                                    @endif
                                </td>
                                <td>
                                    @if($k->status_verifikasi == 1)
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($k->status_verifikasi == 2)
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    @if($k->status_kasus_pengacara == 1)
                                        <span class="badge bg-primary">Dalam Progres</span>
                                    @elseif($k->status_kasus_pengacara == 2)
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">Telaah</span>
                                    @endif
                                </td>
                                <td>
                                    @if($k->status_jadwal_temu == 2 && $k->tgl_waktu_jadwal_temu)
                                        <span class="badge bg-info text-dark">{{ $k->tgl_waktu_jadwal_temu->format('d M, H:i') }}</span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.konsultasi.detail', $k->id_formulir) }}" class="btn btn-sm btn-navy">
                                        <i class="fa-solid fa-sliders me-1"></i> Kelola
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $daftarKonsultasi->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

