@extends('layouts.app')

@section('title', 'Laporan Kasus & Hak SHU Advokat')

@section('styles')
<style>
    @media print {
        .navbar, .footer-law, .no-print, .btn {
            display: none !important;
        }
        body {
            background-color: #FFFFFF !important;
        }
        .card {
            box-shadow: none !important;
            border: none !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Top Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 no-print">
        <div>
            <h3 class="fw-bold text-navy mb-1"><i class="fa-solid fa-file-invoice-dollar text-primary me-2"></i> Rekapitulasi Kasus & Perolehan SHU Advokat</h3>
            <p class="text-muted small mb-0">Laporan penanganan perkara hukum dan transparansi pembagian Sisa Hasil Usaha (SHU) untuk <strong>{{ $user->name }}</strong>.</p>
        </div>
        <button class="btn btn-navy" onclick="window.print()">
            <i class="fa-solid fa-print me-1"></i> Cetak Rekapitulasi
        </button>
    </div>

    <!-- 4 KPI & SHU Cards (No Print) -->
    <div class="row g-3 mb-4 no-print">
        <div class="col-md-3">
            <div class="card card-custom p-3 border-start border-4 border-primary">
                <small class="text-muted fw-semibold">Total Kasus Ditangani</small>
                <h3 class="fw-bold text-navy mb-0 mt-1">{{ $totalKasus }}</h3>
                <small class="text-primary mt-1 d-block"><i class="fa-solid fa-briefcase me-1"></i> Penugasan Perkara</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-3 border-start border-4 border-success">
                <small class="text-muted fw-semibold">Kasus Berhasil / Selesai</small>
                <h3 class="fw-bold text-success mb-0 mt-1">{{ $kasusSelesai }}</h3>
                <small class="text-muted mt-1 d-block"><i class="fa-solid fa-circle-check text-success me-1"></i> Putusan Inkracht / Damai</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-3 border-start border-4 border-info">
                <small class="text-muted fw-semibold">Omset Perkara Ditangani</small>
                <h4 class="fw-bold text-navy mb-0 mt-1">Rp {{ number_format($omsetKasus, 0, ',', '.') }}</h4>
                <small class="text-muted mt-1 d-block"><i class="fa-solid fa-receipt me-1"></i> Tagihan Klien Lunas</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-3 border-start border-4 border-warning bg-warning-subtle">
                <small class="text-dark fw-bold">Total SHU Diterima Anda</small>
                <h4 class="fw-bold text-success mb-0 mt-1">Rp {{ number_format($totalShuAdvokat, 0, ',', '.') }}</h4>
                <small class="text-muted mt-1 d-block"><i class="fa-solid fa-sack-dollar text-warning me-1"></i> Fee 60% + Dividen 15%</small>
            </div>
        </div>
    </div>

    <!-- Alert Rincian SHU Pribadi -->
    <div class="alert alert-light border shadow-sm p-3 mb-4 no-print">
        <div class="d-flex align-items-center">
            <div class="p-3 bg-success-subtle text-success rounded-circle me-3">
                <i class="fa-solid fa-wallet fs-3"></i>
            </div>
            <div>
                <h6 class="fw-bold text-navy mb-1">Rincian Perhitungan Hak SHU Anda:</h6>
                <div class="small text-muted">
                    &bull; <strong>Fee Penanganan Langsung (60%):</strong> Rp {{ number_format($feeLangsung, 0, ',', '.') }} (dari omset perkara yang Anda selesaikan).<br>
                    &bull; <strong>Dividen Pool Kemitraan (15%):</strong> Rp {{ number_format($dividenMitra, 0, ',', '.') }} (bagi hasil merata dari total pendapatan firma).<br>
                    &bull; <strong>Total Hak SHU Bersih Anda:</strong> <span class="fw-bold text-success fs-6">Rp {{ number_format($totalShuAdvokat, 0, ',', '.') }}</span> (Status: Siap Ditransfer ke Rekening Anda).
                </div>
            </div>
        </div>
    </div>

    <!-- Printable Report Sheet -->
    <div class="card card-custom p-4 p-md-5">
        <div class="text-center border-bottom pb-4 mb-4">
            <h4 class="fw-extrabold text-navy mb-0">HANDOKO & PARTNERS LAW FIRM</h4>
            <p class="mb-1 text-muted fw-semibold">REKAPITULASI PENANGANAN PERKARA & HAK SHU ADVOKAT</p>
            <small class="text-muted">Nama Advokat: <strong>{{ $user->name }}</strong> &bull; Periode: Tahun Berjalan &bull; Dicetak: {{ date('d F Y') }}</small>
        </div>

        @if($daftarKasus->isEmpty())
            <div class="text-center py-5">
                <i class="fa-solid fa-folder-closed display-4 text-muted mb-3"></i>
                <h6 class="text-muted">Belum ada perkara yang ditangani.</h6>
            </div>
        @else
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle small">
                    <thead class="table-light text-center">
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>No. Permohonan</th>
                            <th>Tgl Masuk</th>
                            <th>Nama Klien</th>
                            <th>Bidang Hukum</th>
                            <th>Status Progres</th>
                            <th>Status Sidang</th>
                            <th>Nilai Tagihan</th>
                            <th>TTD Berkas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($daftarKasus as $idx => $ks)
                            <tr>
                                <td class="text-center">{{ $idx + 1 }}</td>
                                <td class="fw-bold text-navy">{{ $ks->no_permohonan }}</td>
                                <td>{{ $ks->created_at->format('d/m/Y') }}</td>
                                <td class="fw-bold">{{ $ks->nama }}</td>
                                <td>{{ $ks->klasterisasi->nama_klasterisasi ?? '-' }}</td>
                                <td class="text-center">
                                    @if($ks->status_kasus_pengacara == 1)
                                        <span class="badge bg-primary">Dalam Progres</span>
                                    @elseif($ks->status_kasus_pengacara == 2)
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">Telaah Awal</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $ks->kontrak ? ['Non-Litigasi', 'Gugatan', 'Pembuktian', 'Putusan', 'Inkracht'][$ks->kontrak->status_sidang] : 'Pra-Kontrak' }}
                                </td>
                                <td class="text-end fw-semibold">
                                    @if($ks->pembayaran && $ks->pembayaran->isLunas())
                                        Rp {{ number_format($ks->pembayaran->jumlah_tagihan, 0, ',', '.') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($ks->ttd_konsultan)
                                        <span class="text-success fw-bold"><i class="fa-solid fa-check-circle me-1"></i> Sah</span>
                                    @else
                                        <span class="text-muted">Belum TTD</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="7" class="text-end">TOTAL OMSET KASUS DITANGANI:</td>
                            <td class="text-end text-navy">Rp {{ number_format($omsetKasus, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="7" class="text-end text-success">TOTAL SHU DITERIMA ADVOKAT (FEE 60% + DIVIDEN 15%):</td>
                            <td class="text-end text-success fs-6">Rp {{ number_format($totalShuAdvokat, 0, ',', '.') }}</td>
                            <td class="text-center text-success"><i class="fa-solid fa-check-double me-1"></i> Siap Cair</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="row mt-5 text-center">
                <div class="col-8"></div>
                <div class="col-4">
                    <p class="mb-5 small text-muted">Bandar Lampung, {{ date('d F Y') }}<br>Advokat Penanggung Jawab,</p>
                    <div style="height: 60px;"></div>
                    <p class="fw-bold text-navy mb-0 text-decoration-underline">{{ $user->name }}</p>
                    <small class="text-muted">Advokat & Konsultan Hukum</small>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
