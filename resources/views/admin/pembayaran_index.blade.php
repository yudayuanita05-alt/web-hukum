@extends('layouts.app')

@section('title', 'Manajemen Pembayaran & Gateway')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-navy mb-1"><i class="fa-solid fa-credit-card text-success me-2"></i> Manajemen Pembayaran & Gateway</h3>
            <p class="text-muted small mb-0">Penerbitan tagihan biaya perkara dan monitoring transaksi Midtrans Payment Gateway.</p>
        </div>
        <button class="btn btn-navy" data-bs-toggle="modal" data-bs-target="#modalTambahTagihan">
            <i class="fa-solid fa-plus me-1"></i> Terbitkan Tagihan Baru
        </button>
    </div>

    <!-- Table -->
    <div class="card card-custom p-4">
        @if($daftarPembayaran->isEmpty())
            <div class="text-center py-5">
                <i class="fa-solid fa-receipt display-4 text-muted mb-3"></i>
                <h6 class="text-muted">Belum ada transaksi pembayaran yang tercatat di sistem.</h6>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>No. Permohonan</th>
                            <th>Klien</th>
                            <th>Advokat</th>
                            <th>Nominal Tagihan</th>
                            <th>Kanal Bayar</th>
                            <th>Status Transaksi</th>
                            <th>Waktu Pelunasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($daftarPembayaran as $pb)
                            <tr>
                                <td class="fw-bold text-navy">{{ $pb->order_id }}</td>
                                <td>
                                    <a href="{{ route('admin.konsultasi.detail', $pb->id_formulir) }}" class="text-decoration-none">
                                        {{ $pb->formulir->no_permohonan ?? '-' }}
                                    </a>
                                </td>
                                <td>{{ $pb->formulir->nama ?? '-' }}</td>
                                <td>{{ $pb->formulir->pengacara->name ?? 'Belum Ditugaskan' }}</td>
                                <td class="fw-bold text-success">Rp {{ number_format($pb->jumlah_tagihan, 0, ',', '.') }}</td>
                                <td>
                                    @if($pb->metode_pembayaran)
                                        <span class="badge bg-light text-dark border">{{ strtoupper($pb->metode_pembayaran) }}</span>
                                    @else
                                        <span class="text-muted small">Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pb->isLunas())
                                        <span class="badge bg-success px-2 py-1"><i class="fa-solid fa-check-circle me-1"></i> Settlement / Lunas</span>
                                    @elseif($pb->status == 2)
                                        <span class="badge bg-danger">Expired</span>
                                    @else
                                        <span class="badge bg-warning text-dark px-2 py-1"><i class="fa-solid fa-clock me-1"></i> Pending Gateway</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $pb->waktu_transaksi ? $pb->waktu_transaksi->format('d/m/Y H:i') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $daftarPembayaran->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Terbitkan Tagihan Baru -->
<div class="modal fade" id="modalTambahTagihan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-navy text-white">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-file-invoice-dollar me-2"></i> Terbitkan Tagihan Perkara</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.pembayaran.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Perkara Klien <span class="text-danger">*</span></label>
                        <select name="id_formulir" class="form-select" required>
                            <option value="">-- Pilih Perkara --</option>
                            @foreach($daftarPerkara as $perk)
                                <option value="{{ $perk->id_formulir }}">
                                    {{ $perk->no_permohonan }} - {{ $perk->nama }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hanya menampilkan perkara yang belum memiliki tagihan.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Tagihan Biaya Jasa Hukum (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah_tagihan" class="form-control" placeholder="Contoh: 5000000" min="1000" step="1000" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan Tagihan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Biaya pendampingan hukum dan konsultasi perkara"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-navy">Terbitkan Tagihan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

