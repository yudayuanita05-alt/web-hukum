@extends('layouts.app')

@section('title', 'Kelola Perkara: ' . $formulir->no_permohonan)

@section('content')
<div class="container py-4">
    <!-- Top Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <span class="badge bg-secondary mb-1">Nomor Registrasi: {{ $formulir->no_permohonan }}</span>
            <h3 class="fw-bold text-navy mb-0">Kelola Permohonan & Berkas Perkara</h3>
        </div>
        <div class="d-flex gap-2 mt-3 mt-md-0">
            <a href="{{ route('admin.chat.index', $formulir->id_formulir) }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-comments me-1"></i> Buka Chat Klien
            </a>
            <a href="{{ route('admin.konsultasi.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Kolom Kiri: Rincian Berkas Perkara -->
        <div class="col-lg-7">
            <div class="card card-custom p-4 mb-4">
                <h5 class="fw-bold text-navy mb-3 border-bottom pb-2">
                    <i class="fa-solid fa-user me-2 text-primary"></i> Data Identitas Pemohon
                </h5>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Nama Lengkap</div>
                    <div class="col-sm-8 fw-bold">{{ $formulir->nama }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">TTL</div>
                    <div class="col-sm-8">{{ $formulir->tempat_lahir }}, {{ $formulir->tgl_lahir ? $formulir->tgl_lahir->format('d M Y') : '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Jenis Kelamin / Agama</div>
                    <div class="col-sm-8">{{ $formulir->jenis_kelamin }} / {{ $formulir->agama }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Pekerjaan</div>
                    <div class="col-sm-8">{{ $formulir->pekerjaan }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">No. HP / WhatsApp</div>
                    <div class="col-sm-8">{{ $formulir->no_hp }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Alamat Pemohon</div>
                    <div class="col-sm-8">{{ $formulir->alamat }}</div>
                </div>
                <hr>
                <h6 class="fw-bold text-navy mb-2">Pokok Masalah Hukum</h6>
                <div class="p-3 bg-light rounded-3 text-secondary mb-3" style="white-space: pre-line;">
                    {{ $formulir->uraian_singkat_perkara }}
                </div>

                @if($formulir->bukti_awal)
                    <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 border">
                        <div>
                            <i class="fa-solid fa-paperclip text-primary me-2"></i>
                            <span class="fw-semibold small">Dokumen Bukti Awal Klien</span>
                        </div>
                        <a href="{{ asset('uploads/bukti_awal/' . $formulir->bukti_awal) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-download me-1"></i> Buka Berkas
                        </a>
                    </div>
                @endif
            </div>

            <!-- Tanda Tangan Digital Review -->
            <div class="card card-custom p-4">
                <h5 class="fw-bold text-navy mb-3 border-bottom pb-2">
                    <i class="fa-solid fa-signature me-2 text-warning"></i> Status Tanda Tangan Digital
                </h5>
                <div class="row text-center">
                    <div class="col-6">
                        <small class="text-muted d-block mb-1 fw-semibold">TTD Pemohon (Klien)</small>
                        @if($formulir->ttd_pemohon && file_exists(public_path('uploads/ttd/' . $formulir->ttd_pemohon)))
                            <div class="p-2 border rounded-3 bg-light d-inline-block">
                                <img src="{{ asset('uploads/ttd/' . $formulir->ttd_pemohon) }}" alt="TTD Pemohon" style="max-height: 80px; max-width: 100%;">
                            </div>
                        @else
                            <span class="badge bg-secondary">Tersimpan</span>
                        @endif
                        <div class="small fw-bold mt-1">{{ $formulir->nama }}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block mb-1 fw-semibold">TTD Advokat / Konsultan</small>
                        @if($formulir->ttd_konsultan && file_exists(public_path('uploads/ttd/' . $formulir->ttd_konsultan)))
                            <div class="p-2 border rounded-3 bg-light d-inline-block">
                                <img src="{{ asset('uploads/ttd/' . $formulir->ttd_konsultan) }}" alt="TTD Konsultan" style="max-height: 80px; max-width: 100%;">
                            </div>
                            <div class="small fw-bold mt-1">{{ $formulir->pengacara->name ?? 'Advokat' }}</div>
                        @else
                            <div class="p-3 border rounded-3 bg-light text-muted small fst-italic">
                                Belum ditandatangani oleh advokat
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Panel Aksi Sekretaris -->
        <div class="col-lg-5">
            <!-- 1. Panel Verifikasi & Disposisi Advokat -->
            <div class="card card-custom p-4 mb-4 border-start border-4 border-primary">
                <h5 class="fw-bold text-navy mb-3">1. Verifikasi & Disposisi Advokat</h5>
                <form action="{{ route('admin.konsultasi.verifikasi', $formulir->id_formulir) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Verifikasi</label>
                        <select name="status_verifikasi" class="form-select" required>
                            <option value="0" {{ $formulir->status_verifikasi == 0 ? 'selected' : '' }}>0 - Menunggu Verifikasi</option>
                            <option value="1" {{ $formulir->status_verifikasi == 1 ? 'selected' : '' }}>1 - Disetujui (Lolos Berkas)</option>
                            <option value="2" {{ $formulir->status_verifikasi == 2 ? 'selected' : '' }}>2 - Ditolak (Tidak Lengkap)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tentukan Klaster Perkara</label>
                        <select name="id_klasterisasi" class="form-select">
                            <option value="">-- Pilih Klaster Perkara --</option>
                            @foreach($daftarKlaster as $kl)
                                <option value="{{ $kl->id_klasterisasi }}" {{ $formulir->id_klasterisasi == $kl->id_klasterisasi ? 'selected' : '' }}>
                                    {{ $kl->nama_klasterisasi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tugaskan Advokat / Konsultan</label>
                        <select name="id_user_pengacara" class="form-select">
                            <option value="">-- Pilih Advokat Penanggung Jawab --</option>
                            @foreach($daftarPengacara as $adv)
                                <option value="{{ $adv->id_user }}" {{ $formulir->id_user_pengacara == $adv->id_user ? 'selected' : '' }}>
                                    {{ $adv->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-navy w-100">
                        <i class="fa-solid fa-save me-1"></i> Simpan Verifikasi & Disposisi
                    </button>
                </form>
            </div>

            <!-- 2. Panel Penjadwalan Temu -->
            <div class="card card-custom p-4 mb-4 border-start border-4 border-warning">
                <h5 class="fw-bold text-navy mb-3">2. Penjadwalan Temu Konsultasi</h5>
                <form action="{{ route('admin.konsultasi.jadwal', $formulir->id_formulir) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal & Waktu Janji Temu</label>
                        <input type="datetime-local" name="tgl_waktu_jadwal_temu" class="form-control" value="{{ $formulir->tgl_waktu_jadwal_temu ? $formulir->tgl_waktu_jadwal_temu->format('Y-m-d\TH:i') : '' }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan Tempat / Ruangan</label>
                        <input type="text" name="catatan_jadwal_temu" class="form-control" placeholder="Ruang Konsultasi Kantor Handoko & Partners Law Firm" value="{{ $formulir->catatan_jadwal_temu ?? 'Kantor Handoko & Partners Law Firm, Bandar Lampung' }}">
                    </div>

                    <button type="submit" class="btn btn-warning text-dark fw-bold w-100">
                        <i class="fa-solid fa-calendar-check me-1"></i> Simpan Jadwal Temu
                    </button>
                </form>
            </div>

            <!-- 3. Panel Update Progres Kasus -->
            <div class="card card-custom p-4 mb-4 border-start border-4 border-info">
                <h5 class="fw-bold text-navy mb-3">3. Update Progres Perkara</h5>
                <form action="{{ route('admin.konsultasi.progres', $formulir->id_formulir) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Penanganan Kasus</label>
                        <select name="status_kasus_pengacara" class="form-select">
                            <option value="0" {{ $formulir->status_kasus_pengacara == 0 ? 'selected' : '' }}>0 - Telaah Berkas Awal</option>
                            <option value="1" {{ $formulir->status_kasus_pengacara == 1 ? 'selected' : '' }}>1 - Sedang Ditangani (Dalam Progres)</option>
                            <option value="2" {{ $formulir->status_kasus_pengacara == 2 ? 'selected' : '' }}>2 - Selesai Ditangani</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-info text-white w-100">
                        <i class="fa-solid fa-arrows-rotate me-1"></i> Perbarui Status Progres
                    </button>
                </form>
            </div>

            <!-- 4. Panel Kontrak Kesepakatan & Sidang -->
            <div class="card card-custom p-4 border-start border-4 border-success">
                <h5 class="fw-bold text-navy mb-3">4. Kontrak Kesepakatan & Sidang</h5>
                <form action="{{ route('admin.konsultasi.kontrak', $formulir->id_formulir) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tahapan Persidangan</label>
                        <select name="status_sidang" class="form-select">
                            <option value="0" {{ ($formulir->kontrak->status_sidang ?? 0) == 0 ? 'selected' : '' }}>0 - Non-Litigasi / Mediasi</option>
                            <option value="1" {{ ($formulir->kontrak->status_sidang ?? 0) == 1 ? 'selected' : '' }}>1 - Pendaftaran Gugatan Pengadilan</option>
                            <option value="2" {{ ($formulir->kontrak->status_sidang ?? 0) == 2 ? 'selected' : '' }}>2 - Sidang Pembuktian</option>
                            <option value="3" {{ ($formulir->kontrak->status_sidang ?? 0) == 3 ? 'selected' : '' }}>3 - Sidang Putusan</option>
                            <option value="4" {{ ($formulir->kontrak->status_sidang ?? 0) == 4 ? 'selected' : '' }}>4 - Inkracht / Selesai</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan Kontrak</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Perjanjian Jasa Hukum Penanganan Perkara" value="{{ $formulir->kontrak->keterangan ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Unggah Dokumen Kontrak (PDF)</label>
                        <input type="file" name="berkas_kontrak" class="form-control" accept=".pdf">
                        @if($formulir->kontrak && $formulir->kontrak->berkas_kontrak)
                            <small class="text-success d-block mt-1">
                                <i class="fa-solid fa-check me-1"></i> Berkas kontrak aktif: {{ $formulir->kontrak->berkas_kontrak }}
                            </small>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="fa-solid fa-file-contract me-1"></i> Simpan Dokumen Kontrak
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

