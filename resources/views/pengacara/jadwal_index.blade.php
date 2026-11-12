@extends('layouts.app')

@section('title', 'Agenda Sidang & Jadwal Temu Advokat')

@section('styles')
<!-- FullCalendar v6 CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css">
<style>
    .fc .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--law-navy);
    }
    .fc .fc-button-primary {
        background-color: var(--law-navy);
        border-color: var(--law-navy);
        font-weight: 600;
    }
    .fc .fc-button-primary:hover, .fc .fc-button-primary:focus {
        background-color: var(--law-navy-dark);
        border-color: var(--law-navy-dark);
    }
    .fc .fc-button-active {
        background-color: var(--law-gold) !important;
        border-color: var(--law-gold) !important;
        color: var(--law-navy) !important;
        font-weight: 700 !important;
    }
    .fc-event {
        cursor: pointer;
        border-radius: 6px;
        padding: 3px 5px;
        font-size: 0.8rem;
    }
    .agenda-item {
        border-left: 4px solid var(--law-navy);
        transition: transform 0.2s;
    }
    .agenda-item:hover {
        transform: translateX(4px);
        border-left-color: var(--law-gold);
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 bg-white p-4 rounded-4 shadow-sm border-start border-5 border-info">
        <div>
            <span class="badge bg-info text-dark text-uppercase mb-1"><i class="fa-solid fa-briefcase me-1"></i> Agenda Perkara Advokat</span>
            <h3 class="fw-bold text-navy mb-0">Kalender Agenda Sidang & Pertemuan</h3>
            <p class="text-muted small mb-0 mt-1">Daftar agenda mediasi, sidang pengadilan, dan konsultasi perkara yang didisposisikan kepada Anda.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('pengacara.kasus.index') }}" class="btn btn-navy">
                <i class="fa-solid fa-briefcase me-1"></i> Perkara Saya
            </a>
            <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                <i class="fa-solid fa-print me-1"></i> Cetak Agenda
            </button>
        </div>
    </div>

    <!-- Main Row -->
    <div class="row g-4">
        <!-- Kalender Interaktif -->
        <div class="col-lg-8">
            <div class="card card-custom p-4 shadow-sm h-100">
                <div id="calendar"></div>
            </div>
        </div>

        <!-- Sidebar Agenda -->
        <div class="col-lg-4">
            <div class="card card-custom p-4 shadow-sm mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <h5 class="fw-bold text-navy mb-0">
                        <i class="fa-solid fa-clock-rotate-left text-info me-2"></i> Agenda Mendatang
                    </h5>
                    <span class="badge bg-info text-dark rounded-pill">{{ $agendaMendatang->count() }} Kegiatan</span>
                </div>

                @if($agendaMendatang->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="fa-solid fa-calendar-check display-6 mb-2 text-muted"></i>
                        <p class="small mb-0">Belum ada agenda sidang atau konsultasi yang terjadwal untuk Anda.</p>
                    </div>
                @else
                    <div class="list-group list-group-flush" style="max-height: 460px; overflow-y: auto;">
                        @foreach($agendaMendatang as $item)
                            <div class="list-group-item px-2 py-3 agenda-item mb-2 rounded border bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge bg-navy text-white font-monospace" style="font-size: 0.7rem;">
                                        {{ $item->no_permohonan }}
                                    </span>
                                    <span class="badge bg-warning text-dark fw-bold" style="font-size: 0.7rem;">
                                        <i class="fa-regular fa-clock me-1"></i> {{ $item->tgl_waktu_jadwal_temu->format('H:i') }} WIB
                                    </span>
                                </div>
                                <h6 class="fw-bold text-navy mb-1">{{ $item->nama }}</h6>
                                <div class="small text-muted mb-2">
                                    <i class="fa-regular fa-calendar text-primary me-1"></i>
                                    <strong>{{ $item->tgl_waktu_jadwal_temu->translatedFormat('d F Y') }}</strong>
                                    &bull; {{ $item->klasterisasi->nama_klasterisasi ?? 'Umum' }}
                                </div>
                                <div class="p-2 bg-white rounded border small text-dark mb-2" style="font-size: 0.75rem;">
                                    <i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $item->catatan_jadwal_temu ?? 'Kantor Handoko & Partners Law Firm' }}
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted" style="font-size: 0.72rem;">
                                        <i class="fa-solid fa-phone me-1"></i> {{ $item->no_hp ?? '-' }}
                                    </small>
                                    <a href="{{ route('pengacara.kasus.detail', $item->id_formulir) }}" class="btn btn-sm btn-outline-info py-0 px-2" style="font-size: 0.75rem;">
                                        Buka Perkara &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Catatan Advokat -->
            <div class="card card-custom p-3 bg-light border">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 bg-white text-info rounded-circle shadow-sm">
                        <i class="fa-solid fa-gavel fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-navy mb-1">Kewajiban Pendampingan:</h6>
                        <p class="small text-muted mb-0">Pastikan hadir 15 menit sebelum waktu yang ditentukan dan membawa berkas perkara terkait.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Agenda Advokat -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-navy text-white">
                <h5 class="modal-title fw-bold" id="modalEventTitle">
                    <i class="fa-solid fa-calendar-check text-warning me-2"></i> Detail Agenda Perkara
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <span class="badge bg-info text-dark px-3 py-1 mb-2" id="modalKlaster">Bidang Hukum</span>
                    <h5 class="fw-bold text-navy mb-0" id="modalKlienNama">Nama Klien</h5>
                    <p class="text-muted small mb-0" id="modalNoPermohonan">REG-HKM-000</p>
                </div>

                <div class="bg-light p-3 rounded-3 border mb-3">
                    <div class="row g-2 small">
                        <div class="col-6">
                            <span class="text-muted d-block">Tanggal Pelaksanaan:</span>
                            <strong class="text-dark" id="modalTanggal">-</strong>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block">Waktu / Pukul:</span>
                            <strong class="text-primary" id="modalJam">-</strong>
                        </div>
                        <div class="col-12 mt-2">
                            <span class="text-muted d-block">Kontak Klien (Telepon / WA):</span>
                            <strong class="text-dark" id="modalHp">-</strong>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted mb-1"><i class="fa-solid fa-location-dot text-danger me-1"></i> Tempat / Agenda:</label>
                    <div class="p-2 bg-white border rounded small text-dark" id="modalCatatan">
                        -
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <a href="#" id="modalDetailLink" class="btn btn-navy btn-sm px-3">
                    <i class="fa-solid fa-folder-open me-1"></i> Masuk Ruang Kasus / Berkas
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                list: 'Agenda'
            },
            events: "{{ route('jadwal.events') }}",
            eventClick: function(info) {
                const props = info.event.extendedProps;
                
                document.getElementById('modalEventTitle').innerHTML = '<i class="fa-solid fa-calendar-check text-warning me-2"></i> ' + props.no_permohonan;
                document.getElementById('modalNoPermohonan').innerText = props.no_permohonan;
                document.getElementById('modalKlienNama').innerText = props.klien_nama;
                document.getElementById('modalKlaster').innerText = props.klaster;
                document.getElementById('modalTanggal').innerText = props.tanggal_formatted;
                document.getElementById('modalJam').innerText = props.jam;
                document.getElementById('modalHp').innerText = props.klien_hp || '-';
                document.getElementById('modalCatatan').innerText = props.lokasi_catatan;
                document.getElementById('modalDetailLink').href = props.detail_url;

                eventModal.show();
            }
        });

        calendar.render();
    });
</script>
@endsection

