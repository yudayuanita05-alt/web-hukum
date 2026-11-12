@extends('layouts.app')

@section('title', 'Dashboard Klien')

@section('content')
<div class="container py-4">
    <!-- Header Greeting -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 bg-white p-4 rounded-4 shadow-sm border-start border-5 border-warning">
        <div>
            <h3 class="fw-bold text-navy mb-1">Halo, {{ $user->name }}!</h3>
            <p class="text-muted mb-0 small">Selamat datang di Portal Klien Handoko & Partners Law Firm.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('klien.jadwal.index') }}" class="btn btn-outline-warning text-dark px-3 py-2 fw-semibold">
                <i class="fa-solid fa-calendar-check text-warning me-2"></i> Jadwal Temu Saya
            </a>
            <a href="{{ route('klien.konsultasi.create') }}" class="btn btn-navy px-4 py-2">
                <i class="fa-solid fa-file-circle-plus me-2"></i> Ajukan Konsultasi Baru
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-custom p-4 border-start border-4 border-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Total Pengajuan</span>
                        <h2 class="fw-bold text-navy mb-0 mt-1">{{ $totalPermohonan }}</h2>
                    </div>
                    <div class="p-3 rounded-circle bg-light text-primary">
                        <i class="fa-solid fa-folder-open fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom p-4 border-start border-4 border-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Perkara Sedang Ditangani</span>
                        <h2 class="fw-bold text-warning mb-0 mt-1">{{ $perkaraAktif }}</h2>
                    </div>
                    <div class="p-3 rounded-circle bg-light text-warning">
                        <i class="fa-solid fa-spinner fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom p-4 border-start border-4 border-success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Perkara Selesai</span>
                        <h2 class="fw-bold text-success mb-0 mt-1">{{ $perkaraSelesai }}</h2>
                    </div>
                    <div class="p-3 rounded-circle bg-light text-success">
                        <i class="fa-solid fa-circle-check fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Cases Table -->
    <div class="card card-custom p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-navy mb-0"><i class="fa-solid fa-list-check me-2"></i> Permohonan Konsultasi & Perkara Terkini</h5>
            <a href="{{ route('klien.konsultasi.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
        </div>

        @if($daftarPerkara->isEmpty())
            <div class="text-center py-5">
                <i class="fa-solid fa-folder-open display-4 text-muted mb-3"></i>
                <h6 class="text-muted">Belum ada permohonan konsultasi hukum yang diajukan.</h6>
                <a href="{{ route('klien.konsultasi.create') }}" class="btn btn-sm btn-navy mt-2">
                    <i class="fa-solid fa-plus me-1"></i> Mulai Ajukan Sekarang
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No. Permohonan</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Bidang Perkara</th>
                            <th>Advokat Penanggung Jawab</th>
                            <th>Status Verifikasi</th>
                            <th>Progres Perkara</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($daftarPerkara as $p)
                            <tr>
                                <td class="fw-bold text-navy">{{ $p->no_permohonan }}</td>
                                <td>{{ $p->created_at->format('d M Y') }}</td>
                                <td>{{ $p->klasterisasi->nama_klasterisasi ?? 'Belum Ditentukan' }}</td>
                                <td>
                                    @if($p->pengacara)
                                        <i class="fa-solid fa-user-tie text-muted me-1"></i> {{ $p->pengacara->name }}
                                    @else
                                        <span class="text-muted fst-italic">Menunggu Disposisi</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->status_verifikasi == 1)
                                        <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i> Disetujui</span>
                                    @elseif($p->status_verifikasi == 2)
                                        <span class="badge bg-danger"><i class="fa-solid fa-xmark me-1"></i> Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock me-1"></i> Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->status_kasus_pengacara == 1)
                                        <span class="badge bg-primary">Dalam Progres</span>
                                    @elseif($p->status_kasus_pengacara == 2)
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">Telaah Awal</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('klien.konsultasi.detail', $p->id_formulir) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-eye me-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

