@extends('layouts.app')

@section('title', 'Daftar Permohonan Konsultasi')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-navy mb-1"><i class="fa-solid fa-folder-open me-2"></i> Riwayat Permohonan Konsultasi</h3>
            <p class="text-muted small mb-0">Semua berkas dan permohonan konsultasi hukum yang pernah Anda ajukan.</p>
        </div>
        <a href="{{ route('klien.konsultasi.create') }}" class="btn btn-navy">
            <i class="fa-solid fa-plus me-1"></i> Ajukan Konsultasi Baru
        </a>
    </div>

    <div class="card card-custom p-4">
        @if($perkara->isEmpty())
            <div class="text-center py-5">
                <i class="fa-solid fa-folder-open display-4 text-muted mb-3"></i>
                <h6 class="text-muted">Belum ada riwayat permohonan.</h6>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No. Permohonan</th>
                            <th>Tanggal</th>
                            <th>Pemohon</th>
                            <th>Bidang Hukum</th>
                            <th>Advokat</th>
                            <th>Verifikasi</th>
                            <th>Progres</th>
                            <th>Jadwal Temu</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($perkara as $p)
                            <tr>
                                <td class="fw-bold text-navy">{{ $p->no_permohonan }}</td>
                                <td>{{ $p->created_at->format('d/m/Y') }}</td>
                                <td>{{ $p->nama }}</td>
                                <td>{{ $p->klasterisasi->nama_klasterisasi ?? '-' }}</td>
                                <td>{{ $p->pengacara->name ?? 'Belum Ditugaskan' }}</td>
                                <td>
                                    @if($p->status_verifikasi == 1)
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($p->status_verifikasi == 2)
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->status_kasus_pengacara == 1)
                                        <span class="badge bg-primary">Dalam Progres</span>
                                    @elseif($p->status_kasus_pengacara == 2)
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">Telaah</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->status_jadwal_temu == 2 && $p->tgl_waktu_jadwal_temu)
                                        <span class="badge bg-info text-dark"><i class="fa-solid fa-calendar-check me-1"></i> {{ $p->tgl_waktu_jadwal_temu->format('d M, H:i') }}</span>
                                    @else
                                        <span class="text-muted small">Belum ada</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('klien.konsultasi.detail', $p->id_formulir) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-eye me-1"></i> Lihat
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $perkara->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

