@extends('layouts.app')

@section('title', 'Telaah Perkara: ' . $formulir->no_permohonan)

@section('styles')
<style>
    .signature-container {
        border: 2px dashed #CBD5E1;
        border-radius: 10px;
        background-color: #FFFFFF;
        position: relative;
        text-align: center;
        padding: 10px;
    }
    canvas#signature-pad-advokat {
        width: 100%;
        height: 180px;
        touch-action: none;
        cursor: crosshair;
        background-color: #F8FAFC;
        border-radius: 8px;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Top Action Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <span class="badge bg-secondary mb-1">Perkara No: {{ $formulir->no_permohonan }}</span>
            <h3 class="fw-bold text-navy mb-0">Telaah Perkara & Legalisasi Advokat</h3>
        </div>
        <a href="{{ route('pengacara.kasus.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="row g-4">
        <!-- Rincian Kasus Klien -->
        <div class="col-lg-7">
            <div class="card card-custom p-4 mb-4">
                <h5 class="fw-bold text-navy mb-3 border-bottom pb-2">
                    <i class="fa-solid fa-user-tag me-2 text-primary"></i> Data Pemohon & Masalah Hukum
                </h5>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Nama Klien</div>
                    <div class="col-sm-8 fw-bold">{{ $formulir->nama }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Nomor Kontak</div>
                    <div class="col-sm-8">{{ $formulir->no_hp }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Alamat Klien</div>
                    <div class="col-sm-8">{{ $formulir->alamat }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Pekerjaan</div>
                    <div class="col-sm-8">{{ $formulir->pekerjaan }}</div>
                </div>
                <hr>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Bidang Hukum</div>
                    <div class="col-sm-8 fw-bold text-primary">{{ $formulir->klasterisasi->nama_klasterisasi ?? 'Belum Ditentukan' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Tanggal Kejadian</div>
                    <div class="col-sm-8">{{ $formulir->waktu_pelaksanaan ? $formulir->waktu_pelaksanaan->format('d F Y') : '-' }}</div>
                </div>
                <h6 class="fw-bold text-navy mt-3 mb-2">Kronologi Perkara Klien:</h6>
                <div class="p-3 bg-light rounded-3 text-secondary mb-3" style="white-space: pre-line;">
                    {{ $formulir->uraian_singkat_perkara }}
                </div>

                @if($formulir->bukti_awal)
                    <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center border">
                        <div>
                            <i class="fa-solid fa-paperclip text-primary me-2"></i>
                            <span class="fw-semibold small">Dokumen Bukti Awal dari Klien</span>
                        </div>
                        <a href="{{ asset('uploads/bukti_awal/' . $formulir->bukti_awal) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-eye me-1"></i> Buka Berkas Bukti
                        </a>
                    </div>
                @endif
            </div>

            <!-- Review TTD Pemohon -->
            <div class="card card-custom p-4">
                <h6 class="fw-bold text-navy mb-2"><i class="fa-solid fa-signature text-muted me-2"></i> Tanda Tangan Klien Pemohon</h6>
                <div class="p-3 text-center bg-light rounded-3 border">
                    @if($formulir->ttd_pemohon && file_exists(public_path('uploads/ttd/' . $formulir->ttd_pemohon)))
                        <img src="{{ asset('uploads/ttd/' . $formulir->ttd_pemohon) }}" alt="TTD Klien" style="max-height: 90px; max-width: 100%;">
                    @else
                        <span class="badge bg-secondary">Tanda tangan klien tersimpan</span>
                    @endif
                    <div class="small fw-bold text-navy mt-1">{{ $formulir->nama }}</div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Tanda Tangan Digital Advokat & Status -->
        <div class="col-lg-5">
            <!-- Tanda Tangan Digital Advokat / Konsultan -->
            <div class="card card-custom p-4 mb-4 border-start border-4 border-warning">
                <h5 class="fw-bold text-navy mb-2"><i class="fa-solid fa-pen-fancy text-warning me-2"></i> Legalisasi / TTD Advokat</h5>
                <p class="text-muted small mb-3">Bubuhkan tanda tangan Anda sebagai advokat penanggung jawab perkara untuk melegalisir formulir konsultasi ini.</p>

                @if($formulir->ttd_konsultan && file_exists(public_path('uploads/ttd/' . $formulir->ttd_konsultan)))
                    <div class="alert alert-success p-3 text-center mb-3">
                        <i class="fa-solid fa-circle-check fs-3 text-success mb-2"></i>
                        <h6 class="fw-bold mb-1">Sudah Ditandatangani</h6>
                        <div class="p-2 bg-white rounded-3 border d-inline-block my-2">
                            <img src="{{ asset('uploads/ttd/' . $formulir->ttd_konsultan) }}" alt="TTD Konsultan" style="max-height: 80px; max-width: 100%;">
                        </div>
                        <div class="small fw-bold text-navy">{{ Auth::user()->name }}</div>
                        <small class="text-muted d-block">Dokumen konsultasi telah resmi dilegalisasi.</small>
                    </div>
                @endif

                <form action="{{ route('pengacara.kasus.ttd', $formulir->id_formulir) }}" method="POST" id="formTtdAdvokat">
                    @csrf
                    <input type="hidden" name="signature_data" id="signature_data_advokat">

                    <label class="form-label small fw-semibold mb-1">
                        {{ $formulir->ttd_konsultan ? 'Tanda Tangan Ulang / Perbarui Tanda Tangan:' : 'Goreskan Tanda Tangan Anda di Sini:' }}
                    </label>
                    <div class="signature-container mb-2">
                        <canvas id="signature-pad-advokat"></canvas>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <small class="text-muted"><i class="fa-solid fa-pen me-1"></i> Area Canvas TTD</small>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="btnClearAdvokat">
                            <i class="fa-solid fa-trash me-1"></i> Bersihkan Canvas
                        </button>
                    </div>

                    <button type="submit" class="btn btn-navy w-100 fw-bold">
                        <i class="fa-solid fa-signature me-1"></i> Simpan Tanda Tangan Advokat
                    </button>
                </form>
            </div>

            <!-- Jadwal Temu & Kontrak Perkara -->
            <div class="card card-custom p-4">
                <h6 class="fw-bold text-navy mb-3"><i class="fa-solid fa-calendar-check text-info me-2"></i> Jadwal Temu & Sidang</h6>
                <div class="mb-3">
                    <span class="text-muted small d-block">Jadwal Konsultasi Tatap Muka:</span>
                    @if($formulir->status_jadwal_temu >= 2 && $formulir->tgl_waktu_jadwal_temu)
                        <div class="fw-bold text-navy"><i class="fa-regular fa-clock me-1 text-primary"></i> {{ $formulir->tgl_waktu_jadwal_temu->format('l, d F Y - H:i') }} WIB</div>
                        <small class="text-muted">{{ $formulir->catatan_jadwal_temu ?? 'Kantor Handoko & Partners Law Firm' }}</small>
                    @else
                        <span class="text-muted small fst-italic">Belum dijadwalkan oleh sekretariat.</span>
                    @endif
                </div>

                @if($formulir->kontrak)
                    <hr>
                    <div>
                        <span class="text-muted small d-block">Tahapan Persidangan:</span>
                        <span class="badge bg-success mb-2">{{ ['Non-Litigasi / Mediasi', 'Pendaftaran Gugatan', 'Pembuktian', 'Putusan', 'Inkracht'][$formulir->kontrak->status_sidang] ?? 'Aktif' }}</span>
                        @if($formulir->kontrak->berkas_kontrak)
                            <a href="{{ asset('uploads/kontrak/' . $formulir->kontrak->berkas_kontrak) }}" target="_blank" class="btn btn-sm btn-outline-success d-block">
                                <i class="fa-solid fa-file-pdf me-1"></i> Unduh Berkas Kontrak
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('signature-pad-advokat');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(248, 250, 252)',
            penColor: 'rgb(10, 37, 64)'
        });

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        document.getElementById('btnClearAdvokat').addEventListener('click', function () {
            signaturePad.clear();
        });

        document.getElementById('formTtdAdvokat').addEventListener('submit', function (e) {
            if (signaturePad.isEmpty()) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Tanda Tangan Belum Dibubuhkan',
                    text: 'Goreskan tanda tangan Anda pada canvas terlebih dahulu.',
                    confirmButtonColor: '#0A2540'
                });
                return false;
            }

            const dataUrl = signaturePad.toDataURL('image/png');
            document.getElementById('signature_data_advokat').value = dataUrl;
        });
    });
</script>
@endsection

