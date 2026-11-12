@extends('layouts.app')

@section('title', 'Formulir Permohonan Konsultasi Hukum')

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
    canvas#signature-pad {
        width: 100%;
        height: 200px;
        touch-action: none;
        cursor: crosshair;
        background-color: #F8FAFC;
        border-radius: 8px;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card card-custom p-4 p-md-5 shadow">
                <div class="border-bottom pb-3 mb-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold text-navy mb-1"><i class="fa-solid fa-file-signature text-warning me-2"></i> Formulir Pengajuan Konsultasi Hukum</h3>
                        <p class="text-muted small mb-0">Isi data permohonan dengan jujur dan lengkap untuk dipelajari oleh advokat.</p>
                    </div>
                    <a href="{{ route('klien.konsultasi.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <form action="{{ route('klien.konsultasi.store') }}" method="POST" enctype="multipart/form-data" id="formKonsultasi">
                    @csrf
                    <input type="hidden" name="signature_data" id="signature_data">

                    <!-- Bagian I: Data Identitas Pemohon -->
                    <h5 class="fw-bold text-navy mb-3 border-start border-4 border-warning ps-2">I. Data Identitas Pemohon</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap Pemohon <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor HP / WhatsApp Aktif <span class="text-danger">*</span></label>
                            <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('no_hp') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" name="tempat_lahir" class="form-control" placeholder="Kota Kelahiran" value="{{ old('tempat_lahir') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Agama <span class="text-danger">*</span></label>
                            <input type="text" name="agama" class="form-control" placeholder="Islam / Kristen / dll." value="{{ old('agama', 'Islam') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pekerjaan / Profesi <span class="text-danger">*</span></label>
                            <input type="text" name="pekerjaan" class="form-control" placeholder="Karyawan / Wiraswasta / dll." value="{{ old('pekerjaan') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat Lengkap Sesuai KTP <span class="text-danger">*</span></label>
                            <textarea name="alamat" class="form-control" rows="2" placeholder="Nama Jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten" required>{{ old('alamat') }}</textarea>
                        </div>
                    </div>

                    <!-- Bagian II: Uraian Perkara Hukum -->
                    <h5 class="fw-bold text-navy mb-3 border-start border-4 border-warning ps-2">II. Pokok Perkara & Bukti Awal</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Prakiraan Bidang / Klaster Hukum</label>
                            <select name="id_klasterisasi" class="form-select">
                                <option value="">-- Pilih Jika Mengetahui (Opsional) --</option>
                                @foreach($klasterisasi as $k)
                                    <option value="{{ $k->id_klasterisasi }}" {{ old('id_klasterisasi') == $k->id_klasterisasi ? 'selected' : '' }}>
                                        {{ $k->nama_klasterisasi }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Jika belum pasti, tim kami akan menentukan klaster yang tepat saat verifikasi.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pilih Advokat / Pengacara Pendamping <span class="badge bg-warning text-dark ms-1">Bisa Pilih</span></label>
                            <select name="id_user_pengacara" class="form-select">
                                <option value="">-- Rekomendasi Kantor (Ditentukan Tim Sekretariat) --</option>
                                @foreach($pengacaraList as $p)
                                    <option value="{{ $p->id_user }}" {{ old('id_user_pengacara') == $p->id_user ? 'selected' : '' }}>
                                        ⚖️ {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Anda dapat memilih advokat yang diinginkan untuk menangani perkara ini.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Waktu Pelaksanaan / Terjadinya Kejadian <span class="text-danger">*</span></label>
                            <input type="date" name="waktu_pelaksanaan" class="form-control" value="{{ old('waktu_pelaksanaan', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Uraian Ringkas Kronologi Masalah Hukum <span class="text-danger">*</span></label>
                            <textarea name="uraian_singkat_perkara" class="form-control" rows="5" placeholder="Ceritakan kronologi kejadian, pihak yang terlibat, dan pokok masalah yang ingin Anda konsultasikan..." required>{{ old('uraian_singkat_perkara') }}</textarea>
                            <small class="text-muted">Minimal 20 karakter.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Unggah Dokumen / Berkas Bukti Awal (Opsional)</label>
                            <input type="file" name="bukti_awal" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Format: PDF, JPG, PNG (Maksimal 5 MB). Dapat berupa surat perjanjian, somasi, bukti transaksi, dll.</small>
                        </div>
                    </div>

                    <!-- Bagian III: Tanda Tangan Digital Pemohon -->
                    <h5 class="fw-bold text-navy mb-2 border-start border-4 border-warning ps-2">III. Tanda Tangan Digital Pemohon <span class="text-danger">*</span></h5>
                    <p class="text-muted small mb-3">Bubuhkan tanda tangan Anda pada kotak di bawah ini menggunakan jari (layar sentuh) atau mouse kursor sebagai bentuk legalisir permohonan.</p>

                    <div class="signature-container mb-2">
                        <canvas id="signature-pad"></canvas>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <small class="text-muted"><i class="fa-solid fa-pen-nib me-1"></i> Area Goresan Tanda Tangan Digital</small>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="btnClearSignature">
                            <i class="fa-solid fa-trash me-1"></i> Hapus / Tanda Tangan Ulang
                        </button>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-navy btn-lg py-3 fw-bold" id="btnSubmit">
                            <i class="fa-solid fa-paper-plane me-2"></i> Kirim Permohonan Konsultasi Hukum
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(248, 250, 252)',
            penColor: 'rgb(10, 37, 64)'
        });

        // Resize canvas to parent width
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        document.getElementById('btnClearSignature').addEventListener('click', function () {
            signaturePad.clear();
        });

        document.getElementById('formKonsultasi').addEventListener('submit', function (e) {
            if (signaturePad.isEmpty()) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Tanda Tangan Diperlukan',
                    text: 'Silakan bubuhkan tanda tangan digital Anda pada kotak canvas terlebih dahulu sebelum mengirim permohonan.',
                    confirmButtonColor: '#0A2540'
                });
                return false;
            }

            const dataUrl = signaturePad.toDataURL('image/png');
            document.getElementById('signature_data').value = dataUrl;
        });
    });
</script>
@endsection

