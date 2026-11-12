@extends('layouts.app')

@section('title', 'Perkara Ditangani Advokat')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-navy mb-1"><i class="fa-solid fa-briefcase text-primary me-2"></i> Daftar Perkara Hukum yang Ditugaskan</h3>
            <p class="text-muted small mb-0">Daftar klien dan perkara hukum yang berada dalam tanggung jawab pendampingan Anda.</p>
        </div>
    </div>

    <div class="card card-custom p-4">
        @if($daftarKasus->isEmpty())
            <div class="text-center py-5">
                <i class="fa-solid fa-folder-closed display-4 text-muted mb-3"></i>
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
                            <th>No. HP</th>
                            <th>Bidang Hukum</th>
                            <th>Progres</th>
                            <th>TTD Advokat</th>
                            <th>Jadwal Temu</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($daftarKasus as $k)
                            <tr>
                                <td class="fw-bold text-navy">{{ $k->no_permohonan }}</td>
                                <td>{{ $k->created_at->format('d/m/Y') }}</td>
                                <td class="fw-bold">{{ $k->nama }}</td>
                                <td>{{ $k->no_hp }}</td>
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
                                        <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i> Sudah TTD</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="fa-solid fa-pen me-1"></i> Belum TTD</span>
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
            <div class="mt-3">
                {{ $daftarKasus->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

