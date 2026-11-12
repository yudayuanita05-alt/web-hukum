<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\FormulirKonsultasi;
use App\Models\Klasterisasi;
use App\Models\KontrakKesepakatan;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class KlienController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $totalPermohonan = FormulirKonsultasi::where('id_user', $user->id_user)->count();
        $perkaraAktif = FormulirKonsultasi::where('id_user', $user->id_user)
            ->where('status_kasus_pengacara', 1)
            ->count();
        $perkaraSelesai = FormulirKonsultasi::where('id_user', $user->id_user)
            ->where('status_kasus_pengacara', 2)
            ->count();

        $daftarPerkara = FormulirKonsultasi::where('id_user', $user->id_user)
            ->with(['pengacara', 'klasterisasi', 'pembayaran', 'kontrak'])
            ->orderBy('id_formulir', 'desc')
            ->limit(5)
            ->get();

        return view('klien.dashboard', compact('user', 'totalPermohonan', 'perkaraAktif', 'perkaraSelesai', 'daftarPerkara'));
    }

    public function konsultasiIndex()
    {
        $perkara = FormulirKonsultasi::where('id_user', Auth::user()->id_user)
            ->with(['pengacara', 'klasterisasi', 'pembayaran', 'kontrak'])
            ->orderBy('id_formulir', 'desc')
            ->paginate(10);

        return view('klien.konsultasi_index', compact('perkara'));
    }

    public function konsultasiCreate()
    {
        $klasterisasi = Klasterisasi::all();
        $pengacaraList = User::where('id_role', 2)->where('is_active', 1)->orderBy('id_user', 'asc')->get();
        $user = Auth::user();

        return view('klien.konsultasi_create', compact('klasterisasi', 'pengacaraList', 'user'));
    }

    public function konsultasiStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|string|max:50',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:30',
            'pekerjaan' => 'required|string|max:100',
            'waktu_pelaksanaan' => 'required|date',
            'uraian_singkat_perkara' => 'required|string|min:20',
            'id_klasterisasi' => 'nullable|exists:web_hukum_klasterisasi,id_klasterisasi',
            'id_user_pengacara' => 'nullable|exists:web_hukum_user,id_user',
            'bukti_awal' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'signature_data' => 'required|string', // Base64 Canvas Signature Pad
        ], [
            'signature_data.required' => 'Tanda tangan digital wajib dibubuhkan pada kotak canvas yang tersedia.',
            'uraian_singkat_perkara.min' => 'Uraian perkara minimal 20 karakter agar dapat dipelajari dengan baik oleh advokat.',
            'bukti_awal.max' => 'Ukuran berkas bukti awal maksimal 5 MB.',
        ]);

        $user = Auth::user();
        $noPermohonan = 'REG-HKM-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        // 1. Simpan TTD Digital (Canvas Base64 to PNG)
        $ttdFilename = null;
        if (!empty($request->signature_data) && str_contains($request->signature_data, 'base64,')) {
            $imageParts = explode(';base64,', $request->signature_data);
            $imageBase64 = base64_decode($imageParts[1]);
            $ttdFilename = 'ttd_pemohon_' . $user->id_user . '_' . time() . '.png';
            $destPath = public_path('uploads/ttd');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0777, true);
            }
            file_put_contents($destPath . '/' . $ttdFilename, $imageBase64);
        }

        // 2. Simpan Berkas Bukti Awal
        $buktiFilename = null;
        if ($request->hasFile('bukti_awal')) {
            $file = $request->file('bukti_awal');
            $buktiFilename = 'bukti_' . $user->id_user . '_' . time() . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/bukti_awal');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0777, true);
            }
            $file->move($destPath, $buktiFilename);
        }

        // 3. Simpan Formulir Konsultasi
        $formulir = FormulirKonsultasi::create([
            'id_user' => $user->id_user,
            'id_user_pengacara' => $request->id_user_pengacara ?: null,
            'id_klasterisasi' => $request->id_klasterisasi,
            'no_permohonan' => $noPermohonan,
            'nama' => $request->nama,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'pekerjaan' => $request->pekerjaan,
            'waktu_pelaksanaan' => $request->waktu_pelaksanaan,
            'uraian_singkat_perkara' => $request->uraian_singkat_perkara,
            'ttd_pemohon' => $ttdFilename,
            'ttd_konsultan' => null,
            'bukti_awal' => $buktiFilename,
            'status_verifikasi' => 0, // Menunggu Verifikasi
            'status_kasus_pengacara' => 0, // Masuk/Telaah
            'status_jadwal_temu' => 0, // Belum Ada Jadwal
            'created_at' => now(),
        ]);

        return redirect()->route('klien.konsultasi.detail', $formulir->id_formulir)
            ->with('success', 'Permohonan konsultasi hukum berhasil diajukan dengan nomor: ' . $noPermohonan);
    }

    public function konsultasiDetail($id)
    {
        $formulir = FormulirKonsultasi::where('id_formulir', $id)
            ->where('id_user', Auth::user()->id_user)
            ->with(['pengacara', 'klasterisasi', 'kontrak', 'pembayaran'])
            ->firstOrFail();

        return view('klien.konsultasi_detail', compact('formulir'));
    }

    public function chatIndex($id_formulir = null)
    {
        $user = Auth::user();
        $admin = User::where('id_role', 1)->first();

        $daftarPerkara = FormulirKonsultasi::where('id_user', $user->id_user)
            ->orderBy('id_formulir', 'desc')
            ->get();

        $selectedPerkara = null;
        if ($id_formulir) {
            $selectedPerkara = FormulirKonsultasi::where('id_formulir', $id_formulir)
                ->where('id_user', $user->id_user)
                ->first();
        } elseif ($daftarPerkara->count() > 0) {
            $selectedPerkara = $daftarPerkara->first();
        }

        $chats = collect();
        if ($selectedPerkara && $admin) {
            $chats = Chat::where('id_formulir', $selectedPerkara->id_formulir)
                ->orderBy('id_chat', 'asc')
                ->get();

            // Mark as read for client
            Chat::where('id_formulir', $selectedPerkara->id_formulir)
                ->where('id_penerima', $user->id_user)
                ->update(['is_read' => 1]);
        }

        return view('klien.chat', compact('user', 'admin', 'daftarPerkara', 'selectedPerkara', 'chats'));
    }

    public function chatSend(Request $request)
    {
        $request->validate([
            'id_formulir' => 'required|exists:web_hukum_formulir_konsultasi,id_formulir',
            'pesan' => 'required|string',
        ]);

        $admin = User::where('id_role', 1)->first();
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Admin tidak ditemukan'], 404);
        }

        $chat = Chat::create([
            'id_pengirim' => Auth::user()->id_user,
            'id_penerima' => $admin->id_user,
            'id_formulir' => $request->id_formulir,
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

    public function chatFetch($id_formulir)
    {
        $user = Auth::user();
        $chats = Chat::where('id_formulir', $id_formulir)
            ->with('pengirim')
            ->orderBy('id_chat', 'asc')
            ->get()
            ->map(function ($c) use ($user) {
                return [
                    'id_chat' => $c->id_chat,
                    'pesan' => e($c->pesan),
                    'waktu' => $c->created_at->format('d M, H:i'),
                    'is_sender' => $c->id_pengirim == $user->id_user,
                    'pengirim_nama' => $c->pengirim ? $c->pengirim->name : 'Sistem',
                ];
            });

        return response()->json(['success' => true, 'chats' => $chats]);
    }

    public function pembayaranIndex()
    {
        $user = Auth::user();
        $daftarTagihan = Pembayaran::whereHas('formulir', function ($q) use ($user) {
            $q->where('id_user', $user->id_user);
        })->with('formulir')->orderBy('id_pembayaran', 'desc')->paginate(10);

        return view('klien.pembayaran_index', compact('daftarTagihan'));
    }
}

