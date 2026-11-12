@extends('layouts.app')

@section('title', 'Dashboard Sekretaris / Admin')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 bg-white p-4 rounded-4 shadow-sm border-start border-5 border-primary">
        <div>
            <span class="badge bg-primary text-uppercase mb-1">Panel Sekretariat & Administrasi</span>
            <h3 class="fw-bold text-navy mb-0">Dashboard Manajemen Kantor Hukum</h3>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('admin.konsultasi.index') }}" class="btn btn-navy">
                <i class="fa-solid fa-folder-open me-1"></i> Data Konsultasi
            </a>
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-print me-1"></i> Rekap Laporan
            </a>
        </div>
    </div>

    <!-- Alert Menunggu Verifikasi -->
    @if($menungguVerifikasi > 0)
        <div class="alert alert-warning d-flex align-items-center shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation fs-3 me-3 text-warning"></i>
            <div>
                <strong>Perhatian:</strong> Terdapat <strong>{{ $menungguVerifikasi }}</strong> permohonan konsultasi baru yang menunggu verifikasi dan penugasan advokat.
                <a href="{{ route('admin.konsultasi.index', ['status_verifikasi' => 0]) }}" class="alert-link ms-2">Verifikasi Sekarang &rarr;</a>
            </div>
        </div>
    @endif

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-custom p-3 border-start border-4 border-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold">TOTAL KLIEN</span>
                        <h3 class="fw-bold text-navy mb-0 mt-1">{{ $totalKlien }}</h3>
                    </div>
                    <div class="p-3 rounded-circle bg-light text-primary">
                        <i class="fa-solid fa-users fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-3 border-start border-4 border-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold">TOTAL ADVOKAT</span>
                        <h3 class="fw-bold text-navy mb-0 mt-1">{{ $totalPengacara }}</h3>
                    </div>
                    <div class="p-3 rounded-circle bg-light text-info">
                        <i class="fa-solid fa-user-tie fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-3 border-start border-4 border-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold">KASUS BERJALAN</span>
                        <h3 class="fw-bold text-warning mb-0 mt-1">{{ $perkaraAktif }}</h3>
                    </div>
                    <div class="p-3 rounded-circle bg-light text-warning">
                        <i class="fa-solid fa-spinner fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-3 border-start border-4 border-success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold">PENDAPATAN (GATEWAY)</span>
                        <h5 class="fw-bold text-success mb-0 mt-2">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h5>
                    </div>
                    <div class="p-3 rounded-circle bg-light text-success">
                        <i class="fa-solid fa-wallet fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="row g-4">
        <!-- Konsultasi Baru -->
        <div class="col-lg-7">
            <div class="card card-custom p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-navy mb-0"><i class="fa-solid fa-inbox me-2"></i> Permohonan Masuk Terbaru</h5>
                    <a href="{{ route('admin.konsultasi.index') }}" class="btn btn-sm btn-outline-secondary">Semua</a>
                </div>

                @if($konsultasiTerbaru->isEmpty())
                    <p class="text-muted py-4 text-center">Belum ada permohonan konsultasi.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle small">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Permohonan</th>
                                    <th>Pemohon</th>
                                    <th>Klaster</th>
                                    <th>Verifikasi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($konsultasiTerbaru as $k)
                                    <tr>
                                        <td class="fw-bold text-navy">{{ $k->no_permohonan }}</td>
                                        <td>{{ $k->nama }}</td>
                                        <td>{{ $k->klasterisasi->nama_klasterisasi ?? '-' }}</td>
                                        <td>
                                            @if($k->status_verifikasi == 1)
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($k->status_verifikasi == 2)
                                                <span class="badge bg-danger">Ditolak</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Menunggu</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.konsultasi.detail', $k->id_formulir) }}" class="btn btn-sm btn-outline-primary py-0">
                                                Kelola
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

        <!-- Transaksi Payment Gateway Terbaru -->
        <div class="col-lg-5">
            <div class="card card-custom p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-navy mb-0"><i class="fa-solid fa-receipt me-2"></i> Transaksi Gateway Terbaru</h5>
                    <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-sm btn-outline-secondary">Semua</a>
                </div>

                @if($pembayaranTerbaru->isEmpty())
                    <p class="text-muted py-4 text-center">Belum ada data pembayaran.</p>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($pembayaranTerbaru as $pb)
                            <div class="list-group-item px-0 py-2 border-0 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-bold small text-navy">{{ $pb->order_id }}</span>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            Klien: {{ $pb->formulir->nama ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-success small">Rp {{ number_format($pb->jumlah_tagihan, 0, ',', '.') }}</div>
                                        @if($pb->isLunas())
                                            <span class="badge bg-success" style="font-size: 0.7rem;">Lunas</span>
                                        @else
                                            <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">Pending</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Row Agenda Sidang & Temu Terdekat -->
    <div class="card card-custom p-4 mt-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold text-navy mb-0">
                    <i class="fa-solid fa-calendar-days text-primary me-2"></i> Agenda Sidang & Jadwal Temu Terdekat
                </h5>
                <p class="text-muted small mb-0">Jadwal sidang pengadilan, mediasi perkara, dan konsultasi tatap muka mendatang.</p>
            </div>
            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-sm btn-navy">
                <i class="fa-solid fa-calendar-week me-1"></i> Buka Kalender Lengkap &rarr;
            </a>
        </div>

        @if(isset($agendaMendatang) && $agendaMendatang->isEmpty())
            <div class="text-center py-4 text-muted">
                <i class="fa-regular fa-calendar-check fs-2 mb-2 text-muted"></i>
                <p class="small mb-0">Belum ada agenda jadwal temu yang ditetapkan dalam waktu dekat.</p>
            </div>
        @elseif(isset($agendaMendatang))
            <div class="row g-3">
                @foreach($agendaMendatang as $ag)
                    <div class="col-md-6 col-lg-4">
                        <div class="p-3 bg-light rounded-3 border border-start border-4 border-primary h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge bg-navy font-monospace" style="font-size: 0.7rem;">{{ $ag->no_permohonan }}</span>
                                    <span class="badge bg-warning text-dark fw-bold" style="font-size: 0.7rem;">
                                        <i class="fa-regular fa-clock me-1"></i> {{ $ag->tgl_waktu_jadwal_temu->format('H:i') }} WIB
                                    </span>
                                </div>
                                <h6 class="fw-bold text-navy mb-1">{{ $ag->nama }}</h6>
                                <div class="small text-muted mb-2">
                                    <i class="fa-regular fa-calendar text-primary me-1"></i> <strong>{{ $ag->tgl_waktu_jadwal_temu->translatedFormat('d M Y') }}</strong>
                                    &bull; {{ $ag->klasterisasi->nama_klasterisasi ?? 'Umum' }}
                                </div>
                                <div class="small text-dark bg-white p-2 rounded border mb-2" style="font-size: 0.75rem;">
                                    <i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $ag->catatan_jadwal_temu ?? 'Kantor Handoko & Partners Law Firm' }}
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                <small class="text-muted" style="font-size: 0.72rem;">
                                    <i class="fa-solid fa-user-tie me-1"></i> {{ $ag->pengacara->name ?? 'Belum Ditugaskan' }}
                                </small>
                                <a href="{{ route('admin.konsultasi.detail', $ag->id_formulir) }}" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.75rem;">
                                    Detail &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

