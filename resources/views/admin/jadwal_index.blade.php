@extends('layouts.app')

@section('title', 'Kalender Agenda Sidang & Jadwal Temu')

@section('styles')
<!-- FullCalendar v6 -->
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
        padding: 2px 4px;
        font-size: 0.8rem;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .fc-event:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .agenda-card {
        border-left: 4px solid var(--law-navy);
        transition: all 0.2s ease;
    }
    .agenda-card:hover {
        background-color: #F8FAFC;
        border-left-color: var(--law-gold);
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 bg-white p-4 rounded-4 shadow-sm border-start border-5 border-primary">
        <div>
            <span class="badge bg-primary text-uppercase mb-1"><i class="fa-solid fa-calendar-days me-1"></i> Sekretariat & Penjadwalan</span>
            <h3 class="fw-bold text-navy mb-0">Kalender Agenda Sidang & Jadwal Temu</h3>
            <p class="text-muted small mb-0 mt-1">Manajemen jadwal persidangan, mediasi, dan konsultasi tatap muka seluruh advokat Handoko & Partners Law Firm.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('admin.konsultasi.index') }}" class="btn btn-navy">
                <i class="fa-solid fa-folder-open me-1"></i> Data Konsultasi
            </a>
            <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                <i class="fa-solid fa-print me-1"></i> Cetak Agenda
            </button>
        </div>
    </div>

    <!-- Filter Advokat & Legenda Kategori -->
    <div class="card card-custom p-3 mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-5">
                <form action="{{ route('admin.jadwal.index') }}" method="GET" class="d-flex align-items-center gap-2">
                    <label class="form-label small fw-bold text-navy mb-0 text-nowrap"><i class="fa-solid fa-user-tie me-1"></i> Filter Advokat:</label>
                    <select name="advokat_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Advokat / Seluruh Agenda --</option>
                        @foreach($advokatList as $adv)
                            <option value="{{ $adv->id_user }}" {{ $selectedAdvokat == $adv->id_user ? 'selected' : '' }}>
                                {{ $adv->name }}
                            </option>
                        @endforeach
                    </select>
                    @if($selectedAdvokat)
                        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-sm btn-outline-secondary text-nowrap">Reset</a>
                    @endif
                </form>
            </div>
            <div class="col-md-7">
                <div class="d-flex flex-wrap gap-2 justify-content-md-end align-items-center">
                    <span class="small fw-bold text-muted me-1">Kategori:</span>
                    <span class="badge" style="background:#EF4444;"><i class="fa-solid fa-circle me-1"></i> Pidana</span>
                    <span class="badge" style="background:#2563EB;"><i class="fa-solid fa-circle me-1"></i> Perdata</span>
                    <span class="badge" style="background:#10B981;"><i class="fa-solid fa-circle me-1"></i> Keluarga</span>
                    <span class="badge" style="background:#8B5CF6;"><i class="fa-solid fa-circle me-1"></i> Ketenagakerjaan</span>
                    <span class="badge" style="background:#F59E0B;"><i class="fa-solid fa-circle me-1"></i> Bisnis</span>
                    <span class="badge" style="background:#94A3B8;"><i class="fa-solid fa-circle me-1"></i> Terlewat</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Row: Calendar & Agenda Sidebar -->
    <div class="row g-4">
        <!-- Interactive Calendar (FullCalendar) -->
        <div class="col-lg-8">
            <div class="card card-custom p-4 shadow-sm h-100">
                <div id="calendar" style="min-height: 650px;"></div>
            </div>
        </div>

        <!-- Sidebar: Agenda Terdekat & Mendatang -->
        <div class="col-lg-4">
            <div class="card card-custom p-4 shadow-sm mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-navy mb-0">
                        <i class="fa-solid fa-clock-rotate-left text-primary me-2"></i> Agenda Mendatang
                    </h5>
                    <span class="badge bg-primary rounded-pill">{{ $agendaMendatang->count() }} Jadwal</span>
                </div>

                @if($agendaMendatang->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="fa-solid fa-calendar-xmark display-6 mb-2 text-muted"></i>
                        <p class="small mb-0">Tidak ada jadwal agenda pertemuan atau sidang dalam waktu dekat.</p>
                    </div>
                @else
                    <div class="list-group list-group-flush" style="max-height: 480px; overflow-y: auto;">
                        @foreach($agendaMendatang as $ag)
                            <div class="list-group-item px-2 py-3 agenda-card mb-2 rounded border bg-light">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <span class="badge bg-navy text-white font-monospace" style="font-size: 0.7rem;">
                                        {{ $ag->no_permohonan }}
                                    </span>
                                    <span class="badge bg-warning text-dark fw-bold" style="font-size: 0.7rem;">
                                        <i class="fa-regular fa-clock me-1"></i> {{ $ag->tgl_waktu_jadwal_temu->format('H:i') }} WIB
                                    </span>
                                </div>
                                <h6 class="fw-bold text-navy mb-1">{{ $ag->nama }}</h6>
                                <div class="small text-muted mb-2">
                                    <i class="fa-regular fa-calendar text-primary me-1"></i> 
                                    <strong>{{ $ag->tgl_waktu_jadwal_temu->translatedFormat('d M Y') }}</strong>
                                    &bull; {{ $ag->klasterisasi->nama_klasterisasi ?? 'Umum' }}
                                </div>
                                <div class="small text-dark bg-white p-2 rounded border mb-2" style="font-size: 0.75rem;">
                                    <i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $ag->catatan_jadwal_temu ?? 'Kantor Handoko & Partners Law Firm' }}
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted" style="font-size: 0.72rem;">
                                        <i class="fa-solid fa-user-tie me-1"></i> {{ $ag->pengacara->name ?? 'Belum Ditugaskan' }}
                                    </small>
                                    <a href="{{ route('admin.konsultasi.detail', $ag->id_formulir) }}" class="btn btn-sm btn-outline-navy py-0 px-2" style="font-size: 0.75rem;">
                                        Kelola &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Box Bantuan / Info Cepat -->
            <div class="card card-custom p-3 bg-light border">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 bg-white text-navy rounded-circle shadow-sm">
                        <i class="fa-solid fa-circle-info fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-navy mb-1">Petunjuk Penggunaan:</h6>
                        <p class="small text-muted mb-0">Klik pada salah satu event di kalender untuk melihat detail permohonan, nama klien, kontak, dan mengubah jadwal.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Agenda / Event Popup -->
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
                    <span class="badge bg-primary px-3 py-1 mb-2" id="modalKlaster">Perdata</span>
                    <h5 class="fw-bold text-navy mb-0" id="modalKlienNama">Nama Klien</h5>
                    <p class="text-muted small mb-0" id="modalNoPermohonan">REG-HKM-000</p>
                </div>

                <div class="bg-light p-3 rounded-3 border mb-3">
                    <div class="row g-2 small">
                        <div class="col-6">
                            <span class="text-muted d-block">Tanggal:</span>
                            <strong class="text-dark" id="modalTanggal">-</strong>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block">Waktu / Jam:</span>
                            <strong class="text-primary" id="modalJam">-</strong>
                        </div>
                        <div class="col-12 mt-2">
                            <span class="text-muted d-block">Advokat Penanggung Jawab:</span>
                            <strong class="text-dark" id="modalAdvokat">-</strong>
                        </div>
                        <div class="col-12 mt-2">
                            <span class="text-muted d-block">Nomor Telepon / WA Klien:</span>
                            <strong class="text-dark" id="modalHp">-</strong>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted mb-1"><i class="fa-solid fa-location-dot text-danger me-1"></i> Tempat / Catatan Pertemuan:</label>
                    <div class="p-2 bg-white border rounded small text-dark" id="modalCatatan">
                        -
                    </div>
                </div>

                <!-- Form Ubah Tanggal / Jadwal Cepat -->
                <form id="formReschedule" method="POST" class="border-top pt-3 mt-3">
                    @csrf
                    <h6 class="fw-bold small text-navy mb-2"><i class="fa-solid fa-clock-rotate-left me-1"></i> Reschedule / Ubah Waktu Pertemuan:</h6>
                    <div class="row g-2 mb-2">
                        <div class="col-7">
                            <input type="datetime-local" name="tgl_waktu_jadwal_temu" id="inputNewDatetime" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-5">
                            <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
                            </button>
                        </div>
                    </div>
                    <input type="text" name="catatan_jadwal_temu" id="inputNewCatatan" class="form-control form-control-sm" placeholder="Catatan tempat / ruang temu baru (opsional)">
                </form>
            </div>
            <div class="modal-footer bg-light py-2">
                <a href="#" id="modalDetailLink" class="btn btn-primary btn-sm px-3">
                    <i class="fa-solid fa-folder-open me-1"></i> Buka Berkas Perkara Lengkap
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));

        const advokatId = "{{ $selectedAdvokat ?? '' }}";
        const eventsData = {!! json_encode($eventsJson ?? []) !!};

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            initialDate: '2026-11-20',
            locale: 'id',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                day: 'Hari',
                list: 'Daftar Agenda'
            },
            events: eventsData.length > 0 ? eventsData : "{{ route('jadwal.events') }}" + (advokatId ? '?advokat_id=' + advokatId : ''),
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            eventClick: function(info) {
                const props = info.event.extendedProps;
                
                document.getElementById('modalEventTitle').innerHTML = '<i class="fa-solid fa-calendar-check text-warning me-2"></i> ' + props.no_permohonan;
                document.getElementById('modalNoPermohonan').innerText = props.no_permohonan;
                document.getElementById('modalKlienNama').innerText = props.klien_nama;
                document.getElementById('modalKlaster').innerText = props.klaster;
                document.getElementById('modalTanggal').innerText = props.tanggal_formatted;
                document.getElementById('modalJam').innerText = props.jam;
                document.getElementById('modalAdvokat').innerText = props.advokat_nama;
                document.getElementById('modalHp').innerText = props.klien_hp || '-';
                document.getElementById('modalCatatan').innerText = props.lokasi_catatan;
                document.getElementById('modalDetailLink').href = props.detail_url;

                // Set reschedule action
                const formReschedule = document.getElementById('formReschedule');
                formReschedule.action = "{{ url('admin/jadwal') }}/" + info.event.id + "/update";
                
                // Set default datetime in input
                if (info.event.start) {
                    const dt = new Date(info.event.start);
                    const formatted = dt.toISOString().slice(0, 16);
                    document.getElementById('inputNewDatetime').value = formatted;
                }
                document.getElementById('inputNewCatatan').value = props.lokasi_catatan;

                eventModal.show();
            }
        });

        calendar.render();
    });
</script>
@endsection

