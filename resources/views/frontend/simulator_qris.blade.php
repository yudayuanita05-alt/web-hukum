@extends('layouts.app')

@section('title', 'Simulator QRIS Midtrans Sandbox')

@section('styles')
<style>
    .phone-container {
        max-width: 440px;
        margin: 0 auto;
        border-radius: 36px;
        background: #0B132B;
        padding: 14px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        border: 4px solid #1C2541;
    }
    .phone-screen {
        background: #F8FAFC;
        border-radius: 26px;
        overflow: hidden;
        min-height: 640px;
    }
    .scanner-box {
        position: relative;
        overflow: hidden;
    }
    .scan-line {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #10B981;
        box-shadow: 0 0 10px #10B981;
        animation: scanMove 2.5s infinite alternate ease-in-out;
    }
    @keyframes scanMove {
        0% { top: 5%; }
        100% { top: 92%; }
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="text-center mb-4">
        <span class="badge bg-primary px-3 py-2 rounded-pill fw-bold mb-2">
            <i class="fa-solid fa-vial me-1"></i> Midtrans Payment Sandbox Simulator
        </span>
        <h3 class="fw-bold text-navy">Simulasi Scan & Pembayaran QRIS</h3>
        <p class="text-muted small">Alat simulasi resmi pemindaian QRIS untuk pengujian transaksi Midtrans Sandbox tanpa kartu kredit nyata.</p>
    </div>

    <!-- Phone Simulator Frame -->
    <div class="phone-container">
        <div class="phone-screen p-4 d-flex flex-column justify-content-between">
            <!-- Header Screen -->
            <div>
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-danger">QRIS</span>
                        <small class="fw-bold text-muted">Midtrans Simulator</small>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                        <i class="fa-solid fa-signal me-1"></i> Sandbox Live
                    </span>
                </div>

                <!-- Merchant Info -->
                <div class="text-center mb-3">
                    <span class="badge bg-danger mb-1"><i class="fa-solid fa-qrcode me-1"></i> QRIS Dinamis Otomatis</span>
                    <h5 class="fw-bold text-navy mb-0">NGEBON KUY - GAME</h5>
                    <small class="text-muted">NMID: ID1026634176683 &bull; Order: <strong class="text-dark">{{ $pembayaran->order_id }}</strong></small>
                </div>

                <!-- QR Scanner Area -->
                <div class="p-3 bg-white border rounded-4 text-center shadow-sm mb-3 scanner-box">
                    <div class="scan-line"></div>
                    <img src="{{ $qrImageUrl }}" alt="QRIS Code" class="img-fluid rounded" style="width: 220px; height: 220px;">
                    <div class="mt-2 text-muted fw-bold small">
                        <i class="fa-solid fa-camera me-1"></i> Barcode QRIS Terdeteksi
                    </div>
                </div>

                <!-- Amount Box -->
                <div class="p-3 bg-white border rounded-3 text-center mb-3">
                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">Total Tagihan Pembayaran</small>
                    <h3 class="fw-extrabold text-success mb-0 mt-1">Rp {{ number_format($pembayaran->jumlah_tagihan, 0, ',', '.') }}</h3>
                    <small class="text-muted">{{ $pembayaran->keterangan ?? 'Biaya Jasa Hukum' }}</small>
                </div>

                <!-- Form Konfirmasi Bayar -->
                <form action="{{ route('payment.simulator_qris_pay', $pembayaran->id_pembayaran) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted mb-1">Pilih Sumber Dana (E-Wallet / Bank):</label>
                        <select name="wallet" class="form-select form-select-sm">
                            <option value="GoPay">GoPay (Saldo: Rp 10.000.000)</option>
                            <option value="BCA-Mobile">BCA Mobile / QRIS BCA</option>
                            <option value="DANA">DANA E-Wallet</option>
                            <option value="OVO">OVO Cash</option>
                            <option value="ShopeePay">ShopeePay</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted mb-1">PIN Keamanan (Simulasi):</label>
                        <input type="password" class="form-control form-control-sm text-center font-monospace" value="123456" readonly>
                        <small class="text-muted" style="font-size: 0.7rem;">PIN otomatis terverifikasi untuk mode sandbox.</small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success py-2 fw-bold shadow-sm">
                            <i class="fa-solid fa-fingerprint me-2"></i> Konfirmasi & Bayar Sekarang
                        </button>
                        <a href="{{ route('klien.pembayaran.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-arrow-left me-1"></i> Batal / Kembali ke Tagihan
                        </a>
                    </div>
                </form>
            </div>

            <!-- Footer Screen -->
            <div class="text-center mt-3 pt-2 border-top">
                <small class="text-muted" style="font-size: 0.7rem;">
                    <i class="fa-solid fa-lock me-1"></i> Powered by Midtrans Payment Gateway Sandbox
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

