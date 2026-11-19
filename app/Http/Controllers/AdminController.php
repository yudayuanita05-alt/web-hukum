<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\FormulirKonsultasi;
use App\Models\Klasterisasi;
use App\Models\KontrakKesepakatan;
use App\Models\Pembayaran;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalKlien = User::where('id_role', 3)->count();
        $totalPengacara = User::where('id_role', 2)->count();
        $totalKonsultasi = FormulirKonsultasi::count();
        $perkaraAktif = FormulirKonsultasi::where('status_kasus_pengacara', 1)->count();
        $perkaraSelesai = FormulirKonsultasi::where('status_kasus_pengacara', 2)->count();
        $menungguVerifikasi = FormulirKonsultasi::where('status_verifikasi', 0)->count();

        $totalPendapatan = Pembayaran::where('status', 1)->sum('jumlah_tagihan');

        $konsultasiTerbaru = FormulirKonsultasi::with(['user', 'pengacara', 'klasterisasi'])
            ->orderBy('id_formulir', 'desc')
            ->limit(5)
            ->get();

        $pembayaranTerbaru = Pembayaran::with('formulir.user')
            ->orderBy('id_pembayaran', 'desc')
            ->limit(5)
            ->get();

        $agendaMendatang = FormulirKonsultasi::with(['user', 'pengacara', 'klasterisasi'])
            ->where('status_jadwal_temu', 2)
            ->whereNotNull('tgl_waktu_jadwal_temu')
            ->where('tgl_waktu_jadwal_temu', '>=', now()->startOfDay())
            ->orderBy('tgl_waktu_jadwal_temu', 'asc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalKlien',
            'totalPengacara',
            'totalKonsultasi',
            'perkaraAktif',
            'perkaraSelesai',
            'menungguVerifikasi',
            'totalPendapatan',
            'konsultasiTerbaru',
            'pembayaranTerbaru',
            'agendaMendatang'
        ));
    }

    public function konsultasiIndex(Request $request)
    {
        $query = FormulirKonsultasi::with(['user', 'pengacara', 'klasterisasi', 'kontrak', 'pembayaran']);

        if ($request->filled('status_verifikasi')) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }
        if ($request->filled('status_kasus')) {
            $query->where('status_kasus_pengacara', $request->status_kasus);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%$s%")
                    ->orWhere('no_permohonan', 'like', "%$s%")
                    ->orWhere('no_hp', 'like', "%$s%");
            });
        }

        $daftarKonsultasi = $query->orderBy('id_formulir', 'desc')->paginate(10);
        $daftarPengacara = User::where('id_role', 2)->where('is_active', 1)->get();
        $daftarKlaster = Klasterisasi::all();

        return view('admin.konsultasi_index', compact('daftarKonsultasi', 'daftarPengacara', 'daftarKlaster'));
    }

    public function konsultasiDetail($id)
    {
        $formulir = FormulirKonsultasi::with(['user', 'pengacara', 'klasterisasi', 'kontrak', 'pembayaran'])
            ->findOrFail($id);

        $daftarPengacara = User::where('id_role', 2)->where('is_active', 1)->get();
        $daftarKlaster = Klasterisasi::all();

        return view('admin.konsultasi_detail', compact('formulir', 'daftarPengacara', 'daftarKlaster'));
    }

    public function konsultasiVerifikasi(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:1,2',
            'id_user_pengacara' => 'nullable|exists:web_hukum_user,id_user',
            'id_klasterisasi' => 'nullable|exists:web_hukum_klasterisasi,id_klasterisasi',
        ]);

        $formulir = FormulirKonsultasi::findOrFail($id);
        $formulir->status_verifikasi = $request->status_verifikasi;

        if ($request->status_verifikasi == 1) {
            $formulir->id_user_pengacara = $request->id_user_pengacara;
            $formulir->id_klasterisasi = $request->id_klasterisasi;
            $formulir->status_kasus_pengacara = 1; // Mulai Dalam Progres
            $pesan = 'Permohonan konsultasi berhasil diverifikasi dan didisposisikan ke advokat.';
        } else {
            $formulir->status_kasus_pengacara = 0;
            $pesan = 'Permohonan konsultasi telah ditolak.';
        }

        $formulir->save();

        return back()->with('success', $pesan);
    }

    public function jadwalTemuStore(Request $request, $id)
    {
        $request->validate([
            'tgl_waktu_jadwal_temu' => 'required|date',
            'catatan_jadwal_temu' => 'nullable|string|max:255',
        ]);

        $formulir = FormulirKonsultasi::findOrFail($id);
        $formulir->tgl_waktu_jadwal_temu = $request->tgl_waktu_jadwal_temu;
        $formulir->catatan_jadwal_temu = $request->catatan_jadwal_temu;
        $formulir->status_jadwal_temu = 2; // Terjadwal
        $formulir->save();

        return back()->with('success', 'Jadwal temu konsultasi tatap muka berhasil ditetapkan.');
    }

    public function progresUpdate(Request $request, $id)
    {
        $request->validate([
            'status_kasus_pengacara' => 'required|in:0,1,2',
        ]);

        $formulir = FormulirKonsultasi::findOrFail($id);
        $formulir->status_kasus_pengacara = $request->status_kasus_pengacara;
        $formulir->save();

        return back()->with('success', 'Status progres perkara berhasil diperbarui.');
    }

    public function kontrakUpload(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'nullable|string|max:255',
            'status_sidang' => 'required|integer',
            'berkas_kontrak' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $formulir = FormulirKonsultasi::findOrFail($id);

        $filename = null;
        if ($request->hasFile('berkas_kontrak')) {
            try {
                $file = $request->file('berkas_kontrak');
                $filename = 'kontrak_' . $formulir->id_formulir . '_' . time() . '.pdf';
                $dest = public_path('uploads/kontrak');
                if (!file_exists($dest)) {
                    @mkdir($dest, 0777, true);
                }
                $file->move($dest, $filename);
            } catch (\Throwable $e) {
                // Ignore if storage read-only
            }
        }

        KontrakKesepakatan::updateOrCreate(
            ['id_formulir' => $formulir->id_formulir],
            [
                'keterangan' => $request->keterangan,
                'status_sidang' => $request->status_sidang,
                'berkas_kontrak' => $filename ?? ($formulir->kontrak->berkas_kontrak ?? null),
                'created_at' => now()->toDateString(),
            ]
        );

        return back()->with('success', 'Dokumen kontrak kesepakatan perkara berhasil disimpan.');
    }

    public function pembayaranIndex()
    {
        $daftarPembayaran = Pembayaran::with(['formulir.user', 'formulir.pengacara'])
            ->orderBy('id_pembayaran', 'desc')
            ->paginate(10);

        $daftarPerkara = FormulirKonsultasi::with('user')
            ->whereDoesntHave('pembayaran')
            ->get();

        return view('admin.pembayaran_index', compact('daftarPembayaran', 'daftarPerkara'));
    }

    public function pembayaranStore(Request $request)
    {
        $request->validate([
            'id_formulir' => 'required|exists:web_hukum_formulir_konsultasi,id_formulir|unique:web_hukum_pembayaran,id_formulir',
            'jumlah_tagihan' => 'required|numeric|min:1000',
            'keterangan' => 'nullable|string',
        ], [
            'id_formulir.unique' => 'Perkara ini sudah memiliki tagihan pembayaran.',
        ]);

        $orderId = 'ORDER-HKM-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        Pembayaran::create([
            'id_formulir' => $request->id_formulir,
            'order_id' => $orderId,
            'jumlah_tagihan' => $request->jumlah_tagihan,
            'keterangan' => $request->keterangan,
            'status' => 0, // Pending
            'status_transaksi' => 'pending',
            'created_at' => now(),
        ]);

        return back()->with('success', 'Tagihan pembayaran berhasil diterbitkan dengan Order ID: ' . $orderId);
    }

    public function chatIndex($id_formulir = null)
    {
        $daftarPerkara = FormulirKonsultasi::with('user')
            ->whereHas('chats')
            ->orWhere('status_verifikasi', 1)
            ->orderBy('id_formulir', 'desc')
            ->get();

        $selectedPerkara = null;
        if ($id_formulir) {
            $selectedPerkara = FormulirKonsultasi::with('user')->findOrFail($id_formulir);
        } elseif ($daftarPerkara->count() > 0) {
            $selectedPerkara = $daftarPerkara->first();
        }

        $chats = collect();
        if ($selectedPerkara) {
            $chats = Chat::where('id_formulir', $selectedPerkara->id_formulir)
                ->orderBy('id_chat', 'asc')
                ->get();

            // Mark as read for admin
            Chat::where('id_formulir', $selectedPerkara->id_formulir)
                ->where('id_penerima', Auth::user()->id_user)
                ->update(['is_read' => 1]);
        }

        return view('admin.chat', compact('daftarPerkara', 'selectedPerkara', 'chats'));
    }

    public function chatSend(Request $request)
    {
        $request->validate([
            'id_formulir' => 'required|exists:web_hukum_formulir_konsultasi,id_formulir',
            'pesan' => 'required|string',
        ]);

        $formulir = FormulirKonsultasi::findOrFail($request->id_formulir);

        $chat = Chat::create([
            'id_pengirim' => Auth::user()->id_user,
            'id_penerima' => $formulir->id_user,
            'id_formulir' => $formulir->id_formulir,
            'pesan' => $request->pesan,
            'is_read' => 0,
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'chat' => [
                'id_chat' => $chat->id_chat,
                'pesan' => e($chat->pesan),
                'waktu' => $chat->created_at->format('H:i'),
                'is_sender' => true,
            ]
        ]);
    }

    public function laporanIndex(Request $request)
    {
        $query = FormulirKonsultasi::with(['user', 'pengacara', 'klasterisasi', 'pembayaran', 'kontrak']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }
        if ($request->filled('id_klasterisasi')) {
            $query->where('id_klasterisasi', $request->id_klasterisasi);
        }
        if ($request->filled('status_kasus')) {
            $query->where('status_kasus_pengacara', $request->status_kasus);
        }
        if ($request->filled('id_pengacara')) {
            $query->where('id_user_pengacara', $request->id_pengacara);
        }

        $daftarKasus = $query->orderBy('id_formulir', 'desc')->get();
        $daftarKlaster = Klasterisasi::all();
        $daftarPengacara = User::where('id_role', 2)->where('is_active', 1)->get();

        // 1. STATISTIK PERKARA
        $totalPerkara = FormulirKonsultasi::count();
        $totalSelesai = FormulirKonsultasi::where('status_kasus_pengacara', 2)->count();
        $totalProgres = FormulirKonsultasi::where('status_kasus_pengacara', 1)->count();
        $totalTelaah = FormulirKonsultasi::where('status_kasus_pengacara', 0)->count();
        $winRate = $totalPerkara > 0 ? round(($totalSelesai / $totalPerkara) * 100, 1) : 0;

        // 2. STATISTIK KLASTERISASI
        $statKlaster = Klasterisasi::withCount('formulir')->get();

        // 3. STATISTIK KEUANGAN & PEMBAGIAN SHU (SISA HASIL USAHA) FIRMA HUKUM
        $totalPendapatan = Pembayaran::where('status', 1)->sum('jumlah_tagihan');
        $kasOperasionalPersen = 25; // 25% Biaya Manajemen, Operasional & Pajak Kantor
        $totalKasOperasional = $totalPendapatan * ($kasOperasionalPersen / 100);
        $totalShuBersih = $totalPendapatan - $totalKasOperasional; // 75% Dibagikan ke Advokat

        // Dividen Pool Kemitraan (15% dari total omset dibagi rata ke seluruh partner aktif)
        $totalPoolDividen = $totalPendapatan * 0.15;
        $dividenPerAdvokat = $daftarPengacara->count() > 0 ? ($totalPoolDividen / $daftarPengacara->count()) : 0;

        $statAdvokat = [];
        foreach ($daftarPengacara as $lawyer) {
            $kasusDitangani = FormulirKonsultasi::where('id_user_pengacara', $lawyer->id_user)->count();
            $kasusSelesai = FormulirKonsultasi::where('id_user_pengacara', $lawyer->id_user)
                ->where('status_kasus_pengacara', 2)
                ->count();

            // Total omset perkara yang ditangani advokat ini yang pembayarannya lunas
            $formulirIds = FormulirKonsultasi::where('id_user_pengacara', $lawyer->id_user)->pluck('id_formulir');
            $omsetAdvokat = Pembayaran::whereIn('id_formulir', $formulirIds)
                ->where('status', 1)
                ->sum('jumlah_tagihan');

            // Fee penanganan langsung (60% dari nilai perkara ditangani)
            $feeLangsung = $omsetAdvokat * 0.60;

            // Total SHU diterima advokat (Fee Langsung + Dividen Kemitraan)
            $totalShuDiterima = $feeLangsung + $dividenPerAdvokat;

            $statAdvokat[] = (object)[
                'id_user' => $lawyer->id_user,
                'name' => $lawyer->name,
                'username' => $lawyer->username,
                'kasus_ditangani' => $kasusDitangani,
                'kasus_selesai' => $kasusSelesai,
                'win_rate' => $kasusDitangani > 0 ? round(($kasusSelesai / $kasusDitangani) * 100) : 0,
                'omset_advokat' => $omsetAdvokat,
                'fee_langsung' => $feeLangsung,
                'dividen_mitra' => $dividenPerAdvokat,
                'total_shu' => $totalShuDiterima,
            ];
        }

        return view('admin.laporan_index', compact(
            'daftarKasus',
            'daftarKlaster',
            'daftarPengacara',
            'totalPerkara',
            'totalSelesai',
            'totalProgres',
            'totalTelaah',
            'winRate',
            'statKlaster',
            'totalPendapatan',
            'kasOperasionalPersen',
            'totalKasOperasional',
            'totalShuBersih',
            'totalPoolDividen',
            'dividenPerAdvokat',
            'statAdvokat'
        ));
    }

    public function userIndex()
    {
        $users = User::with('role')->orderBy('id_user', 'desc')->paginate(15);
        $roles = Role::all();

        return view('admin.user_index', compact('users', 'roles'));
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:128',
            'username' => 'required|string|max:128|unique:web_hukum_user,username|alpha_dash',
            'password' => 'required|string|min:6',
            'id_role' => 'required|exists:web_hukum_role,id_role',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'id_role' => $request->id_role,
            'image' => 'default.jpg',
            'is_active' => 1,
            'date_created' => time(),
        ]);

        return back()->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function userToggleStatus($id)
    {
        $user = User::findOrFail($id);
        if ($user->id_user == Auth::user()->id_user) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $user->is_active = $user->is_active ? 0 : 1;
        $user->save();

        return back()->with('success', 'Status akun pengguna berhasil diperbarui.');
    }
}

