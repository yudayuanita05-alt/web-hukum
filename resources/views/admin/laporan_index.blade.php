@extends('layouts.app')

@section('title', 'Laporan, Statistik & SHU Kemitraan Advokat')

@section('styles')
<style>
    @media print {
        .navbar, .footer-law, .no-print, .btn, .nav-tabs, .filter-box {
            display: none !important;
        }
        body {
            background-color: #FFFFFF !important;
            color: #000000 !important;
        }
        .card {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
        }
        .tab-pane {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }
        .print-break-before {
            page-break-before: always;
        }
    }
    .kpi-card {
        border-radius: 12px;
        transition: transform 0.2s ease;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
    }
    .progress-bar-custom {
        height: 10px;
        border-radius: 5px;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Top Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 no-print">
        <div>
            <h3 class="fw-bold text-navy mb-1">
                <i class="fa-solid fa-chart-line text-warning me-2"></i> Laporan Statistik & Sisa Hasil Usaha (SHU)
            </h3>
            <p class="text-muted small mb-0">Analisis kinerja perkara, rekapitulasi penanganan, dan transparansi bagi hasil SHU kemitraan advokat.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" onclick="window.print()">
                <i class="fa-solid fa-print me-1"></i> Cetak Dokumen Resmi
            </button>
        </div>
    </div>

    <!-- Nav Tabs (No Print) -->
    <ul class="nav nav-pills mb-4 no-print gap-2 bg-white p-2 rounded-3 shadow-sm border" id="laporanTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-semibold" id="tab-statistik-btn" data-bs-toggle="pill" data-bs-target="#tab-statistik" type="button" role="tab">
                <i class="fa-solid fa-chart-pie me-1"></i> 1. Statistik & Kinerja Perkara
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="tab-shu-btn" data-bs-toggle="pill" data-bs-target="#tab-shu" type="button" role="tab">
                <i class="fa-solid fa-money-bill-trend-up me-1"></i> 2. Laporan Keuangan & SHU Advokat
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="tab-data-btn" data-bs-toggle="pill" data-bs-target="#tab-data" type="button" role="tab">
                <i class="fa-solid fa-table-list me-1"></i> 3. Rekapitulasi Berkas Perkara
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="laporanTabContent">

        <!-- ========================================== -->
        <!-- TAB 1: STATISTIK & KINERJA PERKARA         -->
        <!-- ========================================== -->
        <div class="tab-pane fade show active" id="tab-statistik" role="tabpanel">
            <!-- 4 KPI Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card card-custom p-3 kpi-card border-start border-4 border-primary">
                        <small class="text-muted fw-semibold">Total Kasus Masuk</small>
                        <h3 class="fw-bold text-navy mb-0 mt-1">{{ $totalPerkara }}</h3>
                        <small class="text-primary mt-2 d-block"><i class="fa-solid fa-folder-open me-1"></i> Seluruh Klaster Hukum</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-custom p-3 kpi-card border-start border-4 border-success">
                        <small class="text-muted fw-semibold">Kasus Selesai (Inkracht)</small>
                        <h3 class="fw-bold text-success mb-0 mt-1">{{ $totalSelesai }}</h3>
                        <small class="text-muted mt-2 d-block"><i class="fa-solid fa-trophy text-warning me-1"></i> Win Rate: <strong>{{ $winRate }}%</strong></small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-custom p-3 kpi-card border-start border-4 border-warning">
                        <small class="text-muted fw-semibold">Perkara Dalam Progres</small>
                        <h3 class="fw-bold text-warning mb-0 mt-1">{{ $totalProgres }}</h3>
                        <small class="text-muted mt-2 d-block"><i class="fa-solid fa-spinner fa-spin me-1"></i> Sedang Ditangani Advokat</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-custom p-3 kpi-card border-start border-4 border-info">
                        <small class="text-muted fw-semibold">Total Penerimaan Jasa Hukum</small>
                        <h4 class="fw-bold text-navy mb-0 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                        <small class="text-success mt-2 d-block"><i class="fa-solid fa-circle-check me-1"></i> Midtrans Settlement 100%</small>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <!-- Distribusi Kasus per Klaster Hukum -->
                <div class="col-lg-6">
                    <div class="card card-custom p-4 h-100">
                        <h5 class="fw-bold text-navy mb-3"><i class="fa-solid fa-scale-balanced text-primary me-2"></i> Distribusi Perkara per Klasterisasi Hukum</h5>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr class="text-muted small">
                                        <th>Klaster Hukum</th>
                                        <th style="width: 120px;" class="text-center">Jumlah Kasus</th>
                                        <th style="width: 150px;">Proporsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($statKlaster as $sk)
                                        @php
                                            $persen = $totalPerkara > 0 ? round(($sk->formulir_count / $totalPerkara) * 100) : 0;
                                        @endphp
                                        <tr>
                                            <td class="fw-semibold text-navy">{{ $sk->nama_klasterisasi }}</td>
                                            <td class="text-center"><span class="badge bg-light text-dark border">{{ $sk->formulir_count }} Kasus</span></td>
                                            <td>
                                                <div class="progress progress-bar-custom bg-light">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $persen }}%"></div>
                                                </div>
                                                <small class="text-muted" style="font-size: 0.75rem;">{{ $persen }}%</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Kinerja Penyelesaian Kasus per Advokat -->
                <div class="col-lg-6">
                    <div class="card card-custom p-4 h-100">
                        <h5 class="fw-bold text-navy mb-3"><i class="fa-solid fa-user-tie text-success me-2"></i> Kinerja & Beban Kasus Tiap Advokat</h5>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr class="text-muted small">
                                        <th>Nama Advokat Partner</th>
                                        <th class="text-center">Ditangani</th>
                                        <th class="text-center">Selesai</th>
                                        <th>Tingkat Sukses</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($statAdvokat as $sa)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-navy">{{ $sa->name }}</div>
                                                <small class="text-muted">Username: {{ $sa->username }}</small>
                                            </td>
                                            <td class="text-center"><span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $sa->kasus_ditangani }}</span></td>
                                            <td class="text-center"><span class="badge bg-success-subtle text-success border border-success-subtle">{{ $sa->kasus_selesai }}</span></td>
                                            <td>
                                                <div class="progress progress-bar-custom bg-light">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $sa->win_rate }}%"></div>
                                                </div>
                                                <small class="text-muted" style="font-size: 0.75rem;">{{ $sa->win_rate }}% Selesai</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: LAPORAN KEUANGAN & SHU ADVOKAT      -->
        <!-- ========================================== -->
        <div class="tab-pane fade" id="tab-shu" role="tabpanel">
            <div class="card card-custom p-4 p-md-5 mb-4">
                <!-- Kop Surat Kantor -->
                <div class="text-center border-bottom pb-4 mb-4">
                    <h3 class="fw-extrabold text-navy mb-0">HANDOKO & PARTNERS LAW FIRM</h3>
                    <p class="mb-1 text-muted fw-semibold">KANTOR ADVOKAT, KURATOR & KONSULTAN HUKUM</p>
                    <small class="text-muted">Bandar Lampung, Provinsi Lampung &bull; Website: handokopartners.law</small>
                    <h5 class="fw-bold text-uppercase mt-4 text-decoration-underline text-navy">
                        LAPORAN KEUANGAN & SISA HASIL USAHA (SHU) KEMITRAAN ADVOKAT
                    </h5>
                    <small class="text-muted">Tahun Buku / Periode Berjalan &bull; Dicetak per: {{ date('d F Y') }}</small>
                </div>

                <!-- Ringkasan Skema Bagi Hasil Firma Hukum -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 border bg-light text-center">
                            <small class="text-muted fw-bold d-block text-uppercase">Total Omset Honorarium Jasa Hukum</small>
                            <h4 class="fw-bold text-navy mt-1 mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                            <small class="text-success"><i class="fa-solid fa-circle-check me-1"></i> Penerimaan Lunas (Settlement)</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 border bg-light text-center">
                            <small class="text-muted fw-bold d-block text-uppercase">Kas Operasional & Pajak Kantor ({{ $kasOperasionalPersen }}%)</small>
                            <h4 class="fw-bold text-danger mt-1 mb-0">Rp {{ number_format($totalKasOperasional, 0, ',', '.') }}</h4>
                            <small class="text-muted">Manajemen, sewa, ATK & administrasi</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 border bg-light text-center border-success">
                            <small class="text-muted fw-bold d-block text-uppercase text-success">Total SHU Dibagikan ke Advokat (75%)</small>
                            <h4 class="fw-bold text-success mt-1 mb-0">Rp {{ number_format($totalShuBersih, 0, ',', '.') }}</h4>
                            <small class="text-success">Fee Langsung 60% + Dividen Mitra 15%</small>
                        </div>
                    </div>
                </div>

                <!-- Info Aturan Pembagian SHU -->
                <div class="alert alert-info py-2 px-3 small mb-4 no-print">
                    <i class="fa-solid fa-circle-info me-1"></i> <strong>Aturan Bagi Hasil SHU Kemitraan:</strong>
                    Advokat penanggung jawab perkara berhak atas <strong>Fee Penanganan Langsung sebesar 60%</strong> dari nilai perkara lunas yang ditanganinya. Sisa <strong>15% dialokasikan sebagai Dividen Pool Kemitraan</strong> yang dibagikan secara merata kepada seluruh advokat partner aktif (Rp {{ number_format($dividenPerAdvokat, 0, ',', '.') }} per advokat).
                </div>

                <!-- Tabel Rincian SHU per Advokat -->
                <h6 class="fw-bold text-navy mb-3">Rincian Perhitungan Pembagian SHU Tiap Advokat Partner:</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle small">
                        <thead class="table-light text-center">
                            <tr>
                                <th style="width: 40px;">No</th>
                                <th>Nama Advokat Partner</th>
                                <th>Kasus Ditangani</th>
                                <th>Kasus Selesai</th>
                                <th>Omset Perkara (Rp)</th>
                                <th>Fee Langsung (60%)</th>
                                <th>Dividen Mitra (15%)</th>
                                <th class="table-success text-success">Total SHU Diterima</th>
                                <th>Status Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $grandTotalShu = 0;
                            @endphp
                            @foreach($statAdvokat as $idx => $sa)
                                @php
                                    $grandTotalShu += $sa->total_shu;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $idx + 1 }}</td>
                                    <td>
                                        <div class="fw-bold text-navy">{{ $sa->name }}</div>
                                        <small class="text-muted">Username: {{ $sa->username }}</small>
                                    </td>
                                    <td class="text-center">{{ $sa->kasus_ditangani }} Perkara</td>
                                    <td class="text-center"><span class="badge bg-success">{{ $sa->kasus_selesai }} Selesai</span></td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($sa->omset_advokat, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($sa->fee_langsung, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($sa->dividen_mitra, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold text-success table-success">
                                        Rp {{ number_format($sa->total_shu, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success"><i class="fa-solid fa-check-double me-1"></i> Siap Ditransfer</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="7" class="text-end">TOTAL SHU YANG DIBAGIKAN KE SELURUH ADVOKAT:</td>
                                <td class="text-end text-success fs-6">Rp {{ number_format($grandTotalShu, 0, ',', '.') }}</td>
                                <td class="text-center text-success">100% Dialokasikan</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Tanda Tangan Resmi Pengesahan SHU -->
                <div class="row mt-5 pt-3 text-center">
                    <div class="col-4">
                        <p class="mb-4 small text-muted">Mengetahui & Menyetujui,<br><strong>Managing Partner</strong></p>
                        <div style="height: 60px;"></div>
                        <p class="fw-bold text-navy mb-0 text-decoration-underline">Ahmad Handoko, S.H., M.H.</p>
                        <small class="text-muted">Managing Partner / Pendiri</small>
                    </div>
                    <div class="col-4">
                        <p class="mb-4 small text-muted">Diverifikasi Oleh,<br><strong>Bagian Keuangan & Pajak</strong></p>
                        <div style="height: 60px;"></div>
                        <p class="fw-bold text-navy mb-0 text-decoration-underline">Dian Safitri, S.H., M.Kn.</p>
                        <small class="text-muted">Partner & Legal Finance</small>
                    </div>
                    <div class="col-4">
                        <p class="mb-4 small text-muted">Dibuat Oleh,<br><strong>Sekretariat Kantor</strong></p>
                        <div style="height: 60px;"></div>
                        <p class="fw-bold text-navy mb-0 text-decoration-underline">Sekretaris Kantor</p>
                        <small class="text-muted">Bandar Lampung, {{ date('d F Y') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: REKAPITULASI BERKAS PERKARA         -->
        <!-- ========================================== -->
        <div class="tab-pane fade" id="tab-data" role="tabpanel">
            <!-- Filter Box (No Print) -->
            <div class="card card-custom p-3 mb-4 no-print filter-box">
                <form method="GET" action="{{ route('admin.laporan.index') }}" class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold mb-1">Klaster Perkara</label>
                        <select name="id_klasterisasi" class="form-select form-select-sm">
                            <option value="">-- Semua Klaster --</option>
                            @foreach($daftarKlaster as $kl)
                                <option value="{{ $kl->id_klasterisasi }}" {{ request('id_klasterisasi') == $kl->id_klasterisasi ? 'selected' : '' }}>
                                    {{ $kl->nama_klasterisasi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold mb-1">Pilih Advokat</label>
                        <select name="id_pengacara" class="form-select form-select-sm">
                            <option value="">-- Semua Advokat --</option>
                            @foreach($daftarPengacara as $adv)
                                <option value="{{ $adv->id_user }}" {{ request('id_pengacara') == $adv->id_user ? 'selected' : '' }}>
                                    {{ $adv->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-1">
                        <button type="submit" class="btn btn-navy btn-sm w-100"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                        <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-rotate-left"></i></a>
                    </div>
                </form>
            </div>

            <!-- Printable Sheet Perkara -->
            <div class="card card-custom p-4 p-md-5">
                <!-- Kop Surat -->
                <div class="text-center border-bottom pb-4 mb-4">
                    <h3 class="fw-extrabold text-navy mb-0">HANDOKO & PARTNERS LAW FIRM</h3>
                    <p class="mb-1 text-muted fw-semibold">KANTOR ADVOKAT, KURATOR & KONSULTAN HUKUM</p>
                    <small class="text-muted">Bandar Lampung, Provinsi Lampung &bull; Website: handokopartners.law</small>
                    <h5 class="fw-bold text-uppercase mt-4 text-decoration-underline text-navy">REKAPITULASI LAPORAN DATA PENANGANAN PERKARA HUKUM</h5>
                    <small class="text-muted">Periode: {{ request('start_date') ? date('d/m/Y', strtotime(request('start_date'))) : 'Awal' }} s/d {{ request('end_date') ? date('d/m/Y', strtotime(request('end_date'))) : 'Hari ini' }}</small>
                </div>

                @if($daftarKasus->isEmpty())
                    <div class="text-center py-5">
                        <i class="fa-solid fa-file-circle-xmark display-4 text-muted mb-3"></i>
                        <h6 class="text-muted">Tidak ada data perkara untuk filter yang dipilih.</h6>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle small">
                            <thead class="table-light text-center">
                                <tr>
                                    <th style="width: 40px;">No</th>
                                    <th>No. Permohonan</th>
                                    <th>Tgl Masuk</th>
                                    <th>Nama Klien</th>
                                    <th>Bidang Hukum</th>
                                    <th>Advokat Penanggung Jawab</th>
                                    <th>Status Kasus</th>
                                    <th>Tagihan / Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($daftarKasus as $index => $ks)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="fw-bold text-navy">{{ $ks->no_permohonan }}</td>
                                        <td>{{ $ks->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $ks->nama }}</td>
                                        <td>{{ $ks->klasterisasi->nama_klasterisasi ?? 'Belum Ditentukan' }}</td>
                                        <td class="fw-semibold">{{ $ks->pengacara->name ?? 'Belum Ditugaskan' }}</td>
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
                                            @if($ks->pembayaran && $ks->pembayaran->isLunas())
                                                <span class="badge bg-success">Lunas Rp {{ number_format($ks->pembayaran->jumlah_tagihan, 0, ',', '.') }}</span>
                                            @elseif($ks->pembayaran)
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-5 text-center">
                        <div class="col-8"></div>
                        <div class="col-4">
                            <p class="mb-5 small text-muted">Bandar Lampung, {{ date('d F Y') }}<br><strong>Sekretariat Handoko & Partners Law Firm</strong></p>
                            <div style="height: 60px;"></div>
                            <p class="fw-bold text-navy mb-0 text-decoration-underline">Sekretaris Kantor</p>
                            <small class="text-muted">NIP / Reg: SEC-2026</small>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
