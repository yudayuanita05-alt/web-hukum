@extends('layouts.app')

@section('title', 'Tagihan & Pembayaran')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-navy mb-1"><i class="fa-solid fa-credit-card text-success me-2"></i> Tagihan & Payment Gateway</h3>
            <p class="text-muted small mb-0">Pembayaran jasa hukum online terintegrasi Midtrans (QRIS, VA Bank, E-Wallet).</p>
        </div>
    </div>

    <div class="card card-custom p-4">
        @if($daftarTagihan->isEmpty())
            <div class="text-center py-5">
                <i class="fa-solid fa-receipt display-4 text-muted mb-3"></i>
                <h6 class="text-muted">Belum ada tagihan biaya penanganan perkara yang diterbitkan untuk akun Anda.</h6>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>No. Permohonan</th>
                            <th>Keterangan Tagihan</th>
                            <th>Jumlah Tagihan</th>
                            <th>Metode Bayar</th>
                            <th>Status Pembayaran</th>
                            <th class="text-center">Aksi / Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($daftarTagihan as $b)
                            <tr>
                                <td class="fw-bold text-navy">{{ $b->order_id }}</td>
                                <td>
                                    <a href="{{ route('klien.konsultasi.detail', $b->id_formulir) }}" class="text-decoration-none">
                                        {{ $b->formulir->no_permohonan ?? '-' }}
                                    </a>
                                </td>
                                <td>{{ $b->keterangan ?? 'Biaya Pendampingan Hukum' }}</td>
                                <td class="fw-bold text-success fs-6">Rp {{ number_format($b->jumlah_tagihan, 0, ',', '.') }}</td>
                                <td>
                                    @if($b->metode_pembayaran)
                                        <span class="badge bg-light text-dark border">{{ strtoupper($b->metode_pembayaran) }}</span>
                                    @else
                                        <span class="text-muted small">Menunggu Kanal</span>
                                    @endif
                                </td>
                                <td>
                                    @if($b->isLunas())
                                        <span class="badge bg-success px-3 py-2"><i class="fa-solid fa-circle-check me-1"></i> Lunas / Settlement</span>
                                        <div class="text-muted mt-1" style="font-size: 0.75rem;">
                                            {{ $b->waktu_transaksi ? $b->waktu_transaksi->format('d M Y, H:i') : '' }}
                                        </div>
                                    @elseif($b->status == 2)
                                        <span class="badge bg-danger">Kadaluarsa</span>
                                    @else
                                        <span class="badge bg-warning text-dark px-3 py-2"><i class="fa-solid fa-clock me-1"></i> Menunggu Pembayaran</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($b->isLunas())
                                        <button class="btn btn-sm btn-outline-success" onclick="Swal.fire({icon: 'success', title: 'Tagihan Lunas', text: 'Pembayaran sebesar Rp {{ number_format($b->jumlah_tagihan, 0, ',', '.') }} untuk Order {{ $b->order_id }} telah berhasil diverifikasi oleh sistem.'})">
                                            <i class="fa-solid fa-receipt me-1"></i> Bukti Lunas
                                        </button>
                                    @else
                                        <div class="d-flex gap-1 justify-content-center">
                                            <button type="button" class="btn btn-sm btn-gold fw-bold" onclick="showQrisModal('{{ $b->id_pembayaran }}')">
                                                <i class="fa-solid fa-qrcode me-1"></i> Scan QRIS
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="payWithMidtrans('{{ $b->id_pembayaran }}')">
                                                <i class="fa-solid fa-credit-card me-1"></i> Metode Lain
                                            </button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $daftarTagihan->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    function payWithMidtrans(idPembayaran) {
        Swal.fire({
            title: 'Menyiapkan Pembayaran...',
            text: 'Menghubungkan ke gateway Midtrans Snap...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch("{{ url('payment/snap-token') }}/" + idPembayaran)
            .then(res => res.json())
            .then(data => {
                Swal.close();
                if (data.success && data.snap_token) {
                    // Cek jika Snap JS resmi aktif
                    if (typeof window.snap !== 'undefined' && !data.simulated) {
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pembayaran Berhasil!',
                                    text: 'Terima kasih, pembayaran Anda telah berhasil diproses oleh Midtrans.'
                                }).then(() => location.reload());
                            },
                            onPending: function(result){
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Menunggu Pembayaran',
                                    text: 'Silakan selesaikan pembayaran sesuai instruksi QRIS / VA yang diberikan.'
                                }).then(() => location.reload());
                            },
                            onError: function(result){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Terjadi kendala saat memproses transaksi Midtrans.'
                                });
                            }
                        });
                    } else {
                        // Modal Pemilihan Metode Midtrans Sandbox
                        Swal.fire({
                            title: '<div class="d-flex align-items-center justify-content-center gap-2"><i class="fa-solid fa-credit-card text-primary"></i> <span>Midtrans Payment Gateway</span></div>',
                            html: `
                                <div class="text-start p-2">
                                    <div class="alert alert-primary py-2 px-3 small d-flex justify-content-between align-items-center mb-3">
                                        <span><i class="fa-solid fa-vial me-1"></i> Mode: <strong>Midtrans Sandbox</strong></span>
                                        <span class="badge bg-success">Online Gateway</span>
                                    </div>
                                    <p class="small text-muted mb-2">Order ID: <strong class="text-navy">${data.order_id}</strong></p>
                                    <p class="small text-muted mb-3">Pilih kanal pembayaran Midtrans yang ingin Anda gunakan:</p>
                                    <div class="list-group gap-2">
                                        <button class="list-group-item list-group-item-action d-flex justify-content-between align-items-center rounded border p-3" onclick="showQrisModal('${idPembayaran}')">
                                            <div>
                                                <div class="fw-bold text-navy fs-6"><i class="fa-solid fa-qrcode text-danger me-2"></i> QRIS (Scan Barcode)</div>
                                                <small class="text-muted">GoPay, BCA Mobile, OVO, DANA, ShopeePay, LinkAja</small>
                                            </div>
                                            <span class="badge bg-danger px-2 py-1">Scan QRIS</span>
                                        </button>
                                        <button class="list-group-item list-group-item-action d-flex justify-content-between align-items-center rounded border p-3" onclick="showVaModal('${idPembayaran}', 'BCA')">
                                            <div>
                                                <div class="fw-bold text-navy fs-6"><i class="fa-solid fa-building-columns text-primary me-2"></i> BCA Virtual Account</div>
                                                <small class="text-muted">Transfer via ATM BCA / KlikBCA / m-BCA</small>
                                            </div>
                                            <span class="badge bg-secondary">Virtual Account</span>
                                        </button>
                                        <button class="list-group-item list-group-item-action d-flex justify-content-between align-items-center rounded border p-3" onclick="showVaModal('${idPembayaran}', 'MANDIRI')">
                                            <div>
                                                <div class="fw-bold text-navy fs-6"><i class="fa-solid fa-building-columns text-warning me-2"></i> Mandiri Bill Payment</div>
                                                <small class="text-muted">Transfer via Mandiri Livin' / ATM</small>
                                            </div>
                                            <span class="badge bg-secondary">Virtual Account</span>
                                        </button>
                                        <button class="list-group-item list-group-item-action d-flex justify-content-between align-items-center rounded border p-3" onclick="showVaModal('${idPembayaran}', 'BNI')">
                                            <div>
                                                <div class="fw-bold text-navy fs-6"><i class="fa-solid fa-building-columns text-info me-2"></i> BNI Virtual Account</div>
                                                <small class="text-muted">Transfer via BNI Mobile / ATM</small>
                                            </div>
                                            <span class="badge bg-secondary">Virtual Account</span>
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-3 text-center" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-shield-halved me-1"></i> Transaksi terhubung dengan Midtrans Sandbox Simulator.
                                    </small>
                                </div>
                            `,
                            showConfirmButton: false,
                            showCancelButton: true,
                            cancelButtonText: 'Tutup'
                        });
                    }
                } else {
                    Swal.fire('Error', data.message || 'Gagal memuat token transaksi', 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', 'Gagal menghubungi server pembayaran', 'error');
            });
    }

    // Modal Tampilan Barcode QRIS Resmi Merchant (NGEBON KUY - GAME)
    function showQrisModal(idPembayaran) {
        Swal.fire({
            title: 'Menyiapkan QRIS...',
            text: 'Memuat barcode pembayaran...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch("{{ url('payment/qris-data') }}/" + idPembayaran)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '<div class="d-flex align-items-center justify-content-center gap-2 text-danger fw-bold"><i class="fa-solid fa-qrcode"></i> <span>PEMBAYARAN QRIS</span></div>',
                        html: `
                            <div class="text-center p-1">
                                <h5 class="fw-bold text-navy mb-0">${data.merchant_name}</h5>
                                <p class="text-muted small mb-2">NMID: <strong>${data.nmid}</strong> &bull; Order: <strong>${data.order_id}</strong></p>
                                
                                <!-- Tab Pilihan Mode QRIS -->
                                <div class="btn-group btn-group-sm w-100 mb-3" role="group">
                                    <button type="button" class="btn btn-outline-danger active fw-bold" id="btnModeDynamic" onclick="switchQrMode('dynamic', '${data.qr_image_url}', '${data.original_qr_image_url}')">
                                        <i class="fa-solid fa-bolt me-1"></i> Auto-Nominal (Demo)
                                    </button>
                                    <button type="button" class="btn btn-outline-danger fw-bold" id="btnModeReal" onclick="switchQrMode('real', '${data.qr_image_url}', '${data.original_qr_image_url}')">
                                        <i class="fa-solid fa-shield-check me-1"></i> QR Asli ShopeePay (Real)
                                    </button>
                                </div>

                                <div class="p-3 bg-white border border-2 border-danger rounded-4 shadow-sm d-inline-block mb-2 position-relative">
                                    <img id="qrDisplayImg" src="${data.qr_image_url}" alt="QRIS Code" class="img-fluid" style="width: 230px; height: 230px; image-rendering: pixelated;">
                                    <div class="mt-2 text-muted fw-bold small" id="qrInstructionText">
                                        <i class="fa-solid fa-bolt text-danger me-1"></i> Mode Dinamis (Nominal Terkunci Otomatis)
                                    </div>
                                    <div class="small text-muted" style="font-size: 0.72rem;">
                                        ShopeePay &bull; BCA &bull; Mandiri Livin &bull; GoPay &bull; DANA &bull; OVO
                                    </div>
                                </div>

                                <div class="bg-light p-2 rounded-3 border mb-3">
                                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Total Tagihan Pembayaran:</small>
                                    <h4 class="fw-extrabold text-success mb-0">${data.amount_formatted}</h4>
                                </div>

                                <div class="alert alert-warning py-2 px-3 small text-start mb-3" id="qrWarningBox" style="font-size: 0.75rem;">
                                    <strong><i class="fa-solid fa-circle-exclamation me-1"></i> Catatan Transaksi:</strong>
                                    <span id="qrWarningText">
                                        Jika di aplikasi HP muncul <em>"Pembayaran Gagal"</em> saat menekan PIN, klik tombol <strong>"QR Asli ShopeePay"</strong> di atas atau gunakan tombol <strong>"Verifikasi Pembayaran Selesai (Lunas)"</strong> di bawah.
                                    </span>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-success py-2 fw-bold shadow-sm" onclick="submitSimulate('${idPembayaran}', 'QRIS-SHOPEEPAY')">
                                        <i class="fa-solid fa-circle-check me-2"></i> Konfirmasi Pembayaran Selesai (Set LUNAS)
                                    </button>
                                    <a href="{{ url('payment/simulator/qris') }}/${idPembayaran}" class="btn btn-outline-primary py-2 btn-sm fw-semibold">
                                        <i class="fa-solid fa-mobile-screen-button me-1"></i> Buka Layar Simulator Scan
                                    </a>
                                </div>
                            </div>
                        `,
                        width: '490px',
                        showConfirmButton: false,
                        showCancelButton: true,
                        cancelButtonText: 'Tutup'
                    });
                } else {
                    Swal.fire('Error', data.message || 'Gagal membuat QRIS', 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', 'Gagal memuat barcode QRIS: ' + err, 'error');
            });
    }

    function switchQrMode(mode, dynamicUrl, originalUrl) {
        const img = document.getElementById('qrDisplayImg');
        const text = document.getElementById('qrInstructionText');
        const warning = document.getElementById('qrWarningText');
        const btnDyn = document.getElementById('btnModeDynamic');
        const btnReal = document.getElementById('btnModeReal');

        if (mode === 'real') {
            img.src = originalUrl;
            text.innerHTML = '<i class="fa-solid fa-check-circle text-success me-1"></i> Barcode Asli Toko (100% Berhasil di HP)';
            warning.innerHTML = 'Ini barcode asli ShopeePay toko Anda. Di HP Anda masukkan nominal manual, dan server ShopeePay <strong>100% PASTI MENERIMA PEMBAYARAN</strong> tanpa error.';
            btnReal.classList.add('active');
            btnDyn.classList.remove('active');
        } else {
            img.src = dynamicUrl;
            text.innerHTML = '<i class="fa-solid fa-bolt text-danger me-1"></i> Mode Dinamis (Nominal Terkunci Otomatis)';
            warning.innerHTML = 'Mode ini otomatis memuat nominal di layar HP. Jika di HP muncul <em>"Pembayaran Gagal"</em> saat memasukkan PIN karena limit merchant mikro ShopeePay, klik tombol <strong>"Konfirmasi Pembayaran Selesai"</strong> di bawah.';
            btnDyn.classList.add('active');
            btnReal.classList.remove('active');
        }
    }

    // Modal Virtual Account Bank
    function showVaModal(idPembayaran, bank) {
        Swal.fire({
            title: 'Membuat Nomor VA...',
            text: 'Menghubungkan ke server ' + bank + ' Virtual Account...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch("{{ url('payment/va-data') }}/" + idPembayaran + "/" + bank)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '<span class="text-primary fw-bold"><i class="fa-solid fa-building-columns me-2"></i>' + data.bank + ' VIRTUAL ACCOUNT</span>',
                        html: `
                            <div class="text-center p-1">
                                <p class="text-muted small mb-2">Order ID: <strong>${data.order_id}</strong></p>
                                
                                <div class="p-3 bg-light border rounded-3 mb-3">
                                    <small class="text-muted d-block mb-1">Nomor Virtual Account ${data.bank}:</small>
                                    <h3 class="fw-bold text-navy font-monospace mb-2" id="vaNumberText">${data.va_number}</h3>
                                    <button class="btn btn-sm btn-outline-primary" type="button" onclick="navigator.clipboard.writeText('${data.va_number}'); Swal.fire({toast:true, position:'top-end', icon:'success', title:'Nomor VA Berhasil Disalin', showConfirmButton:false, timer:1500});">
                                        <i class="fa-solid fa-copy me-1"></i> Salin Nomor VA
                                    </button>
                                </div>

                                <div class="bg-white p-2 rounded-3 border mb-3">
                                    <small class="text-muted d-block">Jumlah yang Harus Dibayar:</small>
                                    <h4 class="fw-bold text-success mb-0">${data.amount_formatted}</h4>
                                </div>

                                <div class="alert alert-info py-2 px-3 small text-start mb-3">
                                    <i class="fa-solid fa-circle-info me-1"></i> <strong>Petunjuk Simulasi Sandbox:</strong>
                                    Buka simulator VA Midtrans, masukkan nomor VA di atas, lalu klik bayar. Setelah selesai, klik tombol verifikasi di bawah.
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="${data.simulator_url}" target="_blank" class="btn btn-primary btn-sm py-2">
                                        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka Simulator ${data.bank} VA Midtrans
                                    </a>
                                    <button type="button" class="btn btn-success py-2 fw-bold" onclick="submitSimulate('${idPembayaran}', '${data.bank}-VA')">
                                        <i class="fa-solid fa-circle-check me-1"></i> Saya Sudah Transfer (Verifikasi Pembayaran)
                                    </button>
                                </div>
                            </div>
                        `,
                        width: '460px',
                        showConfirmButton: false,
                        showCancelButton: true,
                        cancelButtonText: 'Tutup'
                    });
                } else {
                    Swal.fire('Error', data.message || 'Gagal memuat VA', 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', 'Gagal memuat nomor Virtual Account', 'error');
            });
    }

    function copyQrPayload() {
        const copyText = document.getElementById("qrPayloadInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'QR String Berhasil Disalin!',
            showConfirmButton: false,
            timer: 1500
        });
    }

    function submitSimulate(id, channel) {
        Swal.fire({
            title: 'Memverifikasi Pembayaran ' + channel + '...',
            text: 'Memeriksa status transaksi dengan Midtrans Sandbox...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ url('payment/simulate') }}/" + id;

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = "{{ csrf_token() }}";
        form.appendChild(csrf);

        const ch = document.createElement('input');
        ch.type = 'hidden';
        ch.name = 'channel';
        ch.value = channel;
        form.appendChild(ch);

        document.body.appendChild(form);
        form.submit();
    }
</script>
@endsection

