@extends('layouts.app')

@section('title', 'Dashboard Advokat')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 bg-white p-4 rounded-4 shadow-sm border-start border-5 border-info">
        <div>
            <span class="badge bg-info text-dark text-uppercase mb-1">Panel Advokat & Konsultan Hukum</span>
            <h3 class="fw-bold text-navy mb-0">Selamat Datang, {{ $user->name }}</h3>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('pengacara.kasus.index') }}" class="btn btn-navy">
                <i class="fa-solid fa-briefcase me-1"></i> Perkara Saya
            </a>
            <a href="{{ route('pengacara.jadwal.index') }}" class="btn btn-info text-white fw-bold">
                <i class="fa-solid fa-calendar-days me-1"></i> Agenda Sidang & Temu
            </a>
            <a href="{{ route('pengacara.laporan.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-file-lines me-1"></i> Rekap Kasus
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-custom p-3 border-start border-4 border-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold">TOTAL KASUS</span>
                        <h3 class="fw-bold text-navy mb-0 mt-1">{{ $totalKasus }}</h3>
                    </div>
                    <div class="p-3 rounded-circle bg-light text-primary">
                        <i class="fa-solid fa-folder fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-3 border-start border-4 border-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold">DALAM PROGRES</span>
                        <h3 class="fw-bold text-warning mb-0 mt-1">{{ $kasusAktif }}</h3>
                    </div>
                    <div class="p-3 rounded-circle bg-light text-warning">
                        <i class="fa-solid fa-gavel fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-3 border-start border-4 border-success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold">KASUS SELESAI</span>
                        <h3 class="fw-bold text-success mb-0 mt-1">{{ $kasusSelesai }}</h3>
                    </div>
                    <div class="p-3 rounded-circle bg-light text-success">
                        <i class="fa-solid fa-circle-check fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <a href="{{ route('pengacara.jadwal.index') }}" class="text-decoration-none">
                <div class="card card-custom p-3 border-start border-4 border-info h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold">JADWAL KONSULTASI</span>
                            <h3 class="fw-bold text-info mb-0 mt-1">{{ $jadwalTemuMendatang }}</h3>
                        </div>
                        <div class="p-3 rounded-circle bg-light text-info">
                            <i class="fa-solid fa-calendar-check fs-3"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Cases Table -->
    <div class="card card-custom p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-navy mb-0"><i class="fa-solid fa-list-check me-2"></i> Perkara Ditugaskan Terkini</h5>
            <a href="{{ route('pengacara.kasus.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
        </div>

        @if($kasusTerbaru->isEmpty())
            <div class="text-center py-5">
                <i class="fa-solid fa-briefcase display-4 text-muted mb-3"></i>
                <h6 class="text-muted">Belum ada perkara hukum yang didisposisikan kepada Anda.</h6>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No. Permohonan</th>
                            <th>Tanggal Masuk</th>
                            <th>Nama Klien</th>
                            <th>Bidang Hukum</th>
                            <th>Progres</th>
                            <th>TTD Konsultan</th>
                            <th>Jadwal Temu</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kasusTerbaru as $k)
                            <tr>
                                <td class="fw-bold text-navy">{{ $k->no_permohonan }}</td>
                                <td>{{ $k->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="fw-bold">{{ $k->nama }}</div>
                                    <small class="text-muted">{{ $k->no_hp }}</small>
                                </td>
                                <td>{{ $k->klasterisasi->nama_klasterisasi ?? '-' }}</td>
                                <td>
                                    @if($k->status_kasus_pengacara == 1)
                                        <span class="badge bg-primary">Dalam Progres</span>
                                    @elseif($k->status_kasus_pengacara == 2)
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">Telaah Awal</span>
                                    @endif
                                </td>
                                <td>
                                    @if($k->ttd_konsultan)
                                        <span class="badge bg-success"><i class="fa-solid fa-signature me-1"></i> Ditandatangani</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock me-1"></i> Perlu TTD</span>
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
                                    <a href="{{ route('pengacara.kasus.detail', $k->id_formulir) }}" class="btn btn-sm btn-navy">
                                        <i class="fa-solid fa-eye me-1"></i> Telaah & TTD
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

