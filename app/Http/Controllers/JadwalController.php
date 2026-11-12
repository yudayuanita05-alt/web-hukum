<?php

namespace App\Http\Controllers;

use App\Models\FormulirKonsultasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    /**
     * Tampilan Kalender & Agenda untuk Admin / Sekretaris
     */
    public function indexAdmin(Request $request)
    {
        $advokatList = User::where('id_role', 2)->where('status', 1)->get();
        $selectedAdvokat = $request->get('advokat_id');

        // Agenda Terdekat (Mulai hari ini ke depan)
        $agendaMendatang = FormulirKonsultasi::with(['user', 'pengacara', 'klasterisasi'])
            ->where('status_jadwal_temu', 2)
            ->whereNotNull('tgl_waktu_jadwal_temu')
            ->where('tgl_waktu_jadwal_temu', '>=', now()->startOfDay())
            ->when($selectedAdvokat, function ($q) use ($selectedAdvokat) {
                return $q->where('id_user_pengacara', $selectedAdvokat);
            })
            ->orderBy('tgl_waktu_jadwal_temu', 'asc')
            ->take(10)
            ->get();

        // Agenda Lewat / Riwayat
        $riwayatAgenda = FormulirKonsultasi::with(['user', 'pengacara', 'klasterisasi'])
            ->where('status_jadwal_temu', 2)
            ->whereNotNull('tgl_waktu_jadwal_temu')
            ->where('tgl_waktu_jadwal_temu', '<', now()->startOfDay())
            ->orderBy('tgl_waktu_jadwal_temu', 'desc')
            ->take(10)
            ->get();

        return view('admin.jadwal_index', compact('advokatList', 'selectedAdvokat', 'agendaMendatang', 'riwayatAgenda'));
    }

    /**
     * Tampilan Kalender & Agenda untuk Advokat / Pengacara
     */
    public function indexPengacara()
    {
        $user = Auth::user();

        $agendaMendatang = FormulirKonsultasi::with(['user', 'klasterisasi'])
            ->where('id_user_pengacara', $user->id_user)
            ->where('status_jadwal_temu', 2)
            ->whereNotNull('tgl_waktu_jadwal_temu')
            ->where('tgl_waktu_jadwal_temu', '>=', now()->startOfDay())
            ->orderBy('tgl_waktu_jadwal_temu', 'asc')
            ->get();

        $riwayatAgenda = FormulirKonsultasi::with(['user', 'klasterisasi'])
            ->where('id_user_pengacara', $user->id_user)
            ->where('status_jadwal_temu', 2)
            ->whereNotNull('tgl_waktu_jadwal_temu')
            ->where('tgl_waktu_jadwal_temu', '<', now()->startOfDay())
            ->orderBy('tgl_waktu_jadwal_temu', 'desc')
            ->take(10)
            ->get();

        return view('pengacara.jadwal_index', compact('agendaMendatang', 'riwayatAgenda'));
    }

    /**
     * Tampilan Kalender & Agenda untuk Klien
     */
    public function indexKlien()
    {
        $user = Auth::user();

        $agendaList = FormulirKonsultasi::with(['pengacara', 'klasterisasi'])
            ->where('id_user', $user->id_user)
            ->where('status_jadwal_temu', 2)
            ->whereNotNull('tgl_waktu_jadwal_temu')
            ->orderBy('tgl_waktu_jadwal_temu', 'asc')
            ->get();

        return view('klien.jadwal_index', compact('agendaList'));
    }

    /**
     * API JSON Event Feed untuk FullCalendar
     */
    public function getEventsJson(Request $request)
    {
        $user = Auth::user();
        $query = FormulirKonsultasi::with(['user', 'pengacara', 'klasterisasi'])
            ->where('status_jadwal_temu', 2)
            ->whereNotNull('tgl_waktu_jadwal_temu');

        // Filter role
        if ($user->isPengacara()) {
            $query->where('id_user_pengacara', $user->id_user);
        } elseif ($user->isKlien()) {
            $query->where('id_user', $user->id_user);
        } elseif ($user->isAdmin() && $request->filled('advokat_id')) {
            $query->where('id_user_pengacara', $request->advokat_id);
        }

        $list = $query->get();
        $events = [];

        // Palet warna berdasarkan kategori klaster hukum
        $colorPalette = [
            'Pidana' => ['bg' => '#EF4444', 'border' => '#DC2626'], // Merah
            'Perdata' => ['bg' => '#2563EB', 'border' => '#1D4ED8'], // Biru
            'Keluarga' => ['bg' => '#10B981', 'border' => '#059669'], // Hijau
            'Ketenagakerjaan' => ['bg' => '#8B5CF6', 'border' => '#7C3AED'], // Ungu
            'Bisnis' => ['bg' => '#F59E0B', 'border' => '#D97706'], // Emas/Kuning
            'Default' => ['bg' => '#0B132B', 'border' => '#1C2541'], // Navy
        ];

        foreach ($list as $item) {
            $tgl = $item->tgl_waktu_jadwal_temu;
            if (!$tgl) continue;

            $klaster = $item->klasterisasi->nama_klasterisasi ?? 'Umum';
            $colors = $colorPalette['Default'];
            foreach ($colorPalette as $key => $col) {
                if (stripos($klaster, $key) !== false) {
                    $colors = $col;
                    break;
                }
            }

            // Status lampau vs mendatang
            $isPast = $tgl->isPast();

            $events[] = [
                'id' => $item->id_formulir,
                'title' => ($item->no_permohonan ?? 'Konsultasi') . ' - ' . ($item->nama ?? 'Klien'),
                'start' => $tgl->format('Y-m-d\TH:i:s'),
                'end' => $tgl->copy()->addHours(2)->format('Y-m-d\TH:i:s'),
                'backgroundColor' => $isPast ? '#94A3B8' : $colors['bg'],
                'borderColor' => $isPast ? '#64748B' : $colors['border'],
                'textColor' => '#FFFFFF',
                'extendedProps' => [
                    'no_permohonan' => $item->no_permohonan,
                    'klien_nama' => $item->nama,
                    'klien_hp' => $item->no_hp,
                    'advokat_nama' => $item->pengacara->name ?? 'Belum Ditugaskan',
                    'klaster' => $klaster,
                    'lokasi_catatan' => $item->catatan_jadwal_temu ?? 'Kantor Handoko & Partners Law Firm',
                    'jam' => $tgl->format('H:i') . ' WIB',
                    'tanggal_formatted' => $tgl->translatedFormat('l, d F Y'),
                    'is_past' => $isPast,
                    'detail_url' => $user->isAdmin() 
                        ? route('admin.konsultasi.detail', $item->id_formulir)
                        : ($user->isPengacara() 
                            ? route('pengacara.kasus.detail', $item->id_formulir)
                            : route('klien.konsultasi.detail', $item->id_formulir)),
                ]
            ];
        }

        return response()->json($events);
    }

    /**
     * Update Jadwal Cepat (Drag & Drop atau Modal Edit) oleh Admin
     */
    public function updateJadwal(Request $request, $id)
    {
        $formulir = FormulirKonsultasi::findOrFail($id);

        $request->validate([
            'tgl_waktu_jadwal_temu' => 'required|date',
            'catatan_jadwal_temu' => 'nullable|string|max:255',
        ]);

        $formulir->tgl_waktu_jadwal_temu = $request->tgl_waktu_jadwal_temu;
        if ($request->has('catatan_jadwal_temu')) {
            $formulir->catatan_jadwal_temu = $request->catatan_jadwal_temu;
        }
        $formulir->status_jadwal_temu = 2; // Terjadwal
        $formulir->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Jadwal berhasil diperbarui']);
        }

        return back()->with('success', 'Jadwal agenda konsultasi / sidang berhasil diperbarui.');
    }
}

