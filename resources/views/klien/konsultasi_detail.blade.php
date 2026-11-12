@extends('layouts.app')

@section('title', 'Detail Permohonan: ' . $formulir->no_permohonan)

@section('content')
<div class="container py-4">
    <!-- Top Action Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <span class="badge bg-secondary mb-1">Nomor Registrasi Perkara</span>
            <h3 class="fw-bold text-navy mb-0">{{ $formulir->no_permohonan }}</h3>
        </div>
        <div class="d-flex gap-2 mt-3 mt-md-0">
            <a href="{{ route('klien.chat.index', $formulir->id_formulir) }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-comments me-1"></i> Buka Chat Konsultasi
            </a>
            @if($formulir->pembayaran)
                <a href="{{ route('klien.pembayaran.index') }}" class="btn btn-success">
                    <i class="fa-solid fa-credit-card me-1"></i> Tagihan: Rp {{ number_format($formulir->pembayaran->jumlah_tagihan, 0, ',', '.') }}
                </a>
            @endif
            <a href="{{ route('klien.konsultasi.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Timeline Stepper Tracker -->
    <div class="card card-custom p-4 mb-4">
        <h6 class="fw-bold text-navy text-uppercase mb-3"><i class="fa-solid fa-timeline me-2"></i> Status Tahapan Penanganan Perkara</h6>
        <div class="timeline-step">
            <!-- Step 1: Pengajuan -->
            <div class="step-item completed">
                <div class="step-circle"><i class="fa-solid fa-check"></i></div>
                <div class="small fw-bold">Pengajuan</div>
                <div class="text-muted" style="font-size: 0.75rem;">{{ $formulir->created_at->format('d/m/Y') }}</div>
            </div>

            <!-- Step 2: Verifikasi -->
            <div class="step-item {{ $formulir->status_verifikasi == 1 ? 'completed' : ($formulir->status_verifikasi == 2 ? 'bg-danger text-white' : 'active') }}">
                <div class="step-circle">
                    @if($formulir->status_verifikasi == 1)
                        <i class="fa-solid fa-check"></i>
                    @elseif($formulir->status_verifikasi == 2)
                        <i class="fa-solid fa-xmark"></i>
                    @else
                        2
                    @endif
                </div>
                <div class="small fw-bold">Verifikasi</div>
                <div class="text-muted" style="font-size: 0.75rem;">
                    {{ $formulir->status_verifikasi == 1 ? 'Disetujui' : ($formulir->status_verifikasi == 2 ? 'Ditolak' : 'Menunggu') }}
                </div>
            </div>

            <!-- Step 3: Jadwal Temu -->
            <div class="step-item {{ $formulir->status_jadwal_temu >= 2 ? 'completed' : ($formulir->status_verifikasi == 1 ? 'active' : '') }}">
                <div class="step-circle">
                    @if($formulir->status_jadwal_temu >= 2) <i class="fa-solid fa-check"></i> @else 3 @endif
                </div>
                <div class="small fw-bold">Jadwal Temu</div>
                <div class="text-muted" style="font-size: 0.75rem;">
                    {{ $formulir->status_jadwal_temu == 2 ? 'Terjadwal' : ($formulir->status_jadwal_temu == 3 ? 'Selesai Temu' : 'Belum Ada') }}
                </div>
            </div>

            <!-- Step 4: Kontrak -->
            <div class="step-item {{ $formulir->kontrak ? 'completed' : '' }}">
                <div class="step-circle">
                    @if($formulir->kontrak) <i class="fa-solid fa-check"></i> @else 4 @endif
                </div>
                <div class="small fw-bold">Kontrak</div>
                <div class="text-muted" style="font-size: 0.75rem;">
                    {{ $formulir->kontrak ? 'Diterbitkan' : 'Pilihan' }}
                </div>
            </div>

            <!-- Step 5: Sidang / Selesai -->
            <div class="step-item {{ $formulir->status_kasus_pengacara == 2 ? 'completed' : ($formulir->status_kasus_pengacara == 1 ? 'active' : '') }}">
                <div class="step-circle">
                    @if($formulir->status_kasus_pengacara == 2) <i class="fa-solid fa-check"></i> @else 5 @endif
                </div>
                <div class="small fw-bold">Penyelesaian</div>
                <div class="text-muted" style="font-size: 0.75rem;">
                    {{ $formulir->status_kasus_pengacara == 2 ? 'Perkara Selesai' : 'Dalam Progres' }}
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Kolom Kiri: Rincian Data Perkara -->
        <div class="col-lg-8">
            <div class="card card-custom p-4 mb-4">
                <h5 class="fw-bold text-navy mb-3 border-bottom pb-2">
                    <i class="fa-solid fa-file-lines me-2 text-primary"></i> Data Permohonan Konsultasi
                </h5>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Nama Pemohon</div>
                    <div class="col-sm-8 fw-bold">{{ $formulir->nama }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Tempat, Tanggal Lahir</div>
                    <div class="col-sm-8">{{ $formulir->tempat_lahir }}, {{ $formulir->tgl_lahir ? $formulir->tgl_lahir->format('d F Y') : '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Jenis Kelamin / Agama</div>
                    <div class="col-sm-8">{{ $formulir->jenis_kelamin }} / {{ $formulir->agama }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Pekerjaan</div>
                    <div class="col-sm-8">{{ $formulir->pekerjaan }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Nomor Kontak</div>
                    <div class="col-sm-8">{{ $formulir->no_hp }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Alamat Klien</div>
                    <div class="col-sm-8">{{ $formulir->alamat }}</div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Klaster Perkara</div>
                    <div class="col-sm-8 fw-bold text-primary">{{ $formulir->klasterisasi->nama_klasterisasi ?? 'Belum Ditentukan' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Waktu Kejadian</div>
                    <div class="col-sm-8">{{ $formulir->waktu_pelaksanaan ? $formulir->waktu_pelaksanaan->format('d F Y') : '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Uraian Pokok Masalah</div>
                    <div class="col-sm-8 p-3 bg-light rounded-3 text-secondary" style="white-space: pre-line;">
                        {{ $formulir->uraian_singkat_perkara }}
                    </div>
                </div>

                @if($formulir->bukti_awal)
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Dokumen Bukti Awal</div>
                        <div class="col-sm-8">
                            <a href="{{ asset('uploads/bukti_awal/' . $formulir->bukti_awal) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-paperclip me-1"></i> Buka Berkas Bukti Awal
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Tanda Tangan Digital Section -->
            <div class="card card-custom p-4">
                <h5 class="fw-bold text-navy mb-3 border-bottom pb-2">
                    <i class="fa-solid fa-signature me-2 text-warning"></i> Legalisasi & Tanda Tangan Digital
                </h5>
                <div class="row text-center">
                    <div class="col-6">
                        <small class="text-muted d-block mb-2 fw-semibold">Tanda Tangan Pemohon (Klien)</small>
                        @if($formulir->ttd_pemohon && file_exists(public_path('uploads/ttd/' . $formulir->ttd_pemohon)))
                            <div class="p-2 border rounded-3 bg-light d-inline-block">
                                <img src="{{ asset('uploads/ttd/' . $formulir->ttd_pemohon) }}" alt="TTD Pemohon" style="max-height: 100px; max-width: 100%;">
                            </div>
                        @else
                            <span class="badge bg-secondary">Tanda tangan digital tersimpan</span>
                        @endif
                        <div class="small fw-bold text-navy mt-1">{{ $formulir->nama }}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block mb-2 fw-semibold">Tanda Tangan Advokat / Konsultan</small>
                        @if($formulir->ttd_konsultan && file_exists(public_path('uploads/ttd/' . $formulir->ttd_konsultan)))
                            <div class="p-2 border rounded-3 bg-light d-inline-block">
                                <img src="{{ asset('uploads/ttd/' . $formulir->ttd_konsultan) }}" alt="TTD Konsultan" style="max-height: 100px; max-width: 100%;">
                            </div>
                            <div class="small fw-bold text-navy mt-1">{{ $formulir->pengacara->name ?? 'Advokat' }}</div>
                        @else
                            <div class="p-4 border rounded-3 bg-light text-muted small fst-italic">
                                Menunggu tanda tangan pengacara setelah proses konsultasi
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Advokat & Penjadwalan Temu -->
        <div class="col-lg-4">
            <!-- Advokat Info -->
            <div class="card card-custom p-4 mb-4 text-center">
                <h6 class="fw-bold text-navy mb-3">Advokat Penanggung Jawab</h6>
                @if($formulir->pengacara)
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center p-3 mb-2" style="width: 80px; height: 80px; margin: 0 auto; color: var(--law-navy);">
                        <i class="fa-solid fa-user-tie fs-1"></i>
                    </div>
                    <h5 class="fw-bold text-navy mb-1">{{ $formulir->pengacara->name }}</h5>
                    <span class="badge bg-primary mb-3">Advokat & Konsultan Hukum</span>
                    <p class="text-muted small">Ditugaskan untuk menelaah dan mendampingi permasalahan hukum Anda.</p>
                @else
                    <div class="py-4 text-muted">
                        <i class="fa-solid fa-user-clock fs-1 mb-2"></i>
                        <p class="mb-0 small">Belum ada advokat yang ditugaskan. Tim sekretariat sedang memverifikasi berkas Anda.</p>
                    </div>
                @endif
            </div>

            <!-- Jadwal Temu -->
            <div class="card card-custom p-4 mb-4">
                <h6 class="fw-bold text-navy mb-3"><i class="fa-solid fa-calendar-day me-2 text-warning"></i> Jadwal Temu Konsultasi</h6>
                @if($formulir->status_jadwal_temu >= 2 && $formulir->tgl_waktu_jadwal_temu)
                    <div class="p-3 bg-light rounded-3 border-start border-4 border-info">
                        <div class="fw-bold text-navy mb-1">
                            <i class="fa-regular fa-clock me-1"></i> {{ $formulir->tgl_waktu_jadwal_temu->format('l, d F Y - H:i') }} WIB
                        </div>
                        <p class="text-muted small mb-0">
                            <strong>Lokasi/Catatan:</strong> {{ $formulir->catatan_jadwal_temu ?? 'Kantor Handoko & Partners Law Firm' }}
                        </p>
                    </div>
                @else
                    <p class="text-muted small mb-0">
                        Jadwal temu tatap muka akan ditetapkan oleh sekretaris setelah permohonan disetujui.
                    </p>
                @endif
            </div>

            <!-- Kontrak Kesepakatan -->
            @if($formulir->kontrak)
                <div class="card card-custom p-4 mb-4 border-start border-4 border-success">
                    <h6 class="fw-bold text-navy mb-2"><i class="fa-solid fa-file-contract me-2 text-success"></i> Kontrak Kesepakatan</h6>
                    <p class="text-muted small mb-2">{{ $formulir->kontrak->keterangan ?? 'Kontrak pendampingan hukum resmi telah diterbitkan.' }}</p>
                    <div class="mb-3">
                        <span class="badge bg-success">Status Sidang: {{ ['Non-Litigasi', 'Pendaftaran Gugatan', 'Pembuktian', 'Putusan', 'Inkracht'][$formulir->kontrak->status_sidang] ?? 'Aktif' }}</span>
                    </div>
                    @if($formulir->kontrak->berkas_kontrak)
                        <a href="{{ asset('uploads/kontrak/' . $formulir->kontrak->berkas_kontrak) }}" target="_blank" class="btn btn-sm btn-outline-success w-100">
                            <i class="fa-solid fa-download me-1"></i> Unduh Berkas Kontrak
                        </a>
                    @endif
                </div>
            @endif

            <!-- Tagihan & Payment Gateway Midtrans -->
            @if($formulir->pembayaran)
                <div class="card card-custom p-4 mb-4 border-start border-4 {{ $formulir->pembayaran->isLunas() ? 'border-success' : 'border-warning' }}">
                    <h6 class="fw-bold text-navy mb-2"><i class="fa-solid fa-credit-card me-2 text-primary"></i> Tagihan Midtrans Gateway</h6>
                    <p class="text-muted small mb-1">{{ $formulir->pembayaran->keterangan ?? 'Biaya pendampingan hukum.' }}</p>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="small text-muted">Jumlah Tagihan:</span>
                        <span class="fw-bold text-success fs-6">Rp {{ number_format($formulir->pembayaran->jumlah_tagihan, 0, ',', '.') }}</span>
                    </div>
                    @if($formulir->pembayaran->isLunas())
                        <div class="badge bg-success w-100 py-2"><i class="fa-solid fa-circle-check me-1"></i> Lunas / Settlement ({{ strtoupper($formulir->pembayaran->metode_pembayaran ?? 'Gateway') }})</div>
                    @else
                        <a href="{{ route('klien.pembayaran.index') }}" class="btn btn-gold btn-sm w-100 fw-bold">
                            <i class="fa-solid fa-qrcode me-1"></i> Bayar Sekarang via Midtrans
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

