@extends('layouts.app')

@section('title', 'Jadwal Temu & Konsultasi Hukum')

@section('styles')
<!-- FullCalendar v6 CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css">
<style>
    .fc .fc-toolbar-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--law-navy);
    }
    .fc .fc-button-primary {
        background-color: var(--law-navy);
        border-color: var(--law-navy);
    }
    .fc .fc-button-active {
        background-color: var(--law-gold) !important;
        border-color: var(--law-gold) !important;
        color: var(--law-navy) !important;
    }
    .fc-event {
        cursor: pointer;
        border-radius: 6px;
        padding: 3px 5px;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 bg-white p-4 rounded-4 shadow-sm border-start border-5 border-warning">
        <div>
            <span class="badge bg-warning text-dark text-uppercase mb-1"><i class="fa-solid fa-calendar-check me-1"></i> Jadwal Konsultasi Saya</span>
            <h3 class="fw-bold text-navy mb-0">Agenda Pertemuan Konsultasi Hukum</h3>
            <p class="text-muted small mb-0 mt-1">Daftar jadwal konsultasi langsung dan pendampingan bersama Advokat Handoko & Partners Law Firm.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('klien.konsultasi.index') }}" class="btn btn-navy">
                <i class="fa-solid fa-file-circle-plus me-1"></i> Permohonan Saya
            </a>
        </div>
    </div>

    <!-- Main Row -->
    <div class="row g-4">
        <!-- Interactive Calendar -->
        <div class="col-lg-7">
            <div class="card card-custom p-4 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <h5 class="fw-bold text-navy mb-0"><i class="fa-solid fa-calendar-days text-primary me-2"></i> Kalender Jadwal</h5>
                    <span class="badge bg-light text-muted border">Klik tanggal untuk detail</span>
                </div>
                <div id="calendar"></div>
            </div>
        </div>

        <!-- Daftar Jadwal Temu & Petunjuk -->
        <div class="col-lg-5">
            <div class="card card-custom p-4 shadow-sm mb-4">
                <h5 class="fw-bold text-navy mb-3 border-bottom pb-2">
                    <i class="fa-solid fa-clock text-warning me-2"></i> Rincian Jadwal Temu Anda
                </h5>

                @if($agendaList->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-calendar-plus display-5 mb-2 text-muted"></i>
                        <h6 class="fw-bold">Belum Ada Jadwal Temu Ditetapkan</h6>
                        <p class="small">Sekretariat kantor hukum sedang memverifikasi permohonan Anda untuk menetapkan jadwal pertemuan.</p>
                        <a href="{{ route('klien.konsultasi.create') }}" class="btn btn-sm btn-navy mt-2">
                            <i class="fa-solid fa-plus me-1"></i> Ajukan Konsultasi Sekarang
                        </a>
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($agendaList as $item)
                            <div class="list-group-item px-3 py-3 rounded border mb-3 {{ $item->tgl_waktu_jadwal_temu->isPast() ? 'bg-light' : 'bg-white border-primary shadow-sm' }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-navy font-monospace">{{ $item->no_permohonan }}</span>
                                    @if($item->tgl_waktu_jadwal_temu->isPast())
                                        <span class="badge bg-secondary">Selesai / Terlewat</span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="fa-regular fa-clock me-1"></i> {{ $item->tgl_waktu_jadwal_temu->format('H:i') }} WIB
                                        </span>
                                    @endif
                                </div>

                                <h6 class="fw-bold text-navy mb-1">{{ $item->klasterisasi->nama_klasterisasi ?? 'Konsultasi Perkara' }}</h6>

                                <div class="p-2 bg-light rounded border mb-2 small">
                                    <div class="text-muted"><i class="fa-regular fa-calendar text-primary me-1"></i> Hari & Tanggal:</div>
                                    <strong class="text-dark fs-6">{{ $item->tgl_waktu_jadwal_temu->translatedFormat('l, d F Y') }}</strong>
                                </div>

                                <div class="p-2 bg-light rounded border mb-2 small">
                                    <div class="text-muted"><i class="fa-solid fa-user-tie text-info me-1"></i> Advokat Pendamping:</div>
                                    <strong class="text-dark">{{ $item->pengacara->name ?? 'Tim Advokat Handoko & Partners' }}</strong>
                                </div>

                                <div class="p-2 bg-light rounded border mb-3 small">
                                    <div class="text-muted"><i class="fa-solid fa-location-dot text-danger me-1"></i> Tempat Pertemuan:</div>
                                    <span class="text-dark">{{ $item->catatan_jadwal_temu ?? 'Kantor Handoko & Partners Law Firm, Jl. Soekarno Hatta, Bandar Lampung' }}</span>
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('klien.konsultasi.detail', $item->id_formulir) }}" class="btn btn-sm btn-navy flex-grow-1">
                                        <i class="fa-solid fa-eye me-1"></i> Lihat Berkas Permohonan
                                    </a>
                                    <a href="{{ route('klien.chat.index', $item->id_formulir) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-comments"></i> Chat Advokat
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Petunjuk Kedatangan Klien -->
            <div class="card card-custom p-4 bg-light border">
                <h6 class="fw-bold text-navy mb-2"><i class="fa-solid fa-circle-exclamation text-warning me-2"></i> Petunjuk Pertemuan:</h6>
                <ul class="small text-muted ps-3 mb-0">
                    <li class="mb-1">Harap hadir <strong>15 menit</strong> sebelum jadwal konsultasi dimulai.</li>
                    <li class="mb-1">Bawalah <strong>Kartu Identitas (KTP/SIM)</strong> asli saat hadir di kantor.</li>
                    <li class="mb-1">Bawa seluruh dokumen asli atau bukti awal pendukung perkara Anda.</li>
                    <li>Jika berhalangan hadir, hubungi kami melalui fitur <strong>Konsultasi Chat</strong> minimal 1 hari sebelumnya.</li>
                </ul>
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

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,listMonth'
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                list: 'Agenda'
            },
            events: "{{ route('jadwal.events') }}",
            eventClick: function(info) {
                if (info.event.extendedProps.detail_url) {
                    window.location.href = info.event.extendedProps.detail_url;
                }
            }
        });

        calendar.render();
    });
</script>
@endsection

