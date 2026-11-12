<?php

namespace App\Http\Controllers;

use App\Models\FormulirKonsultasi;
use App\Models\Klasterisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengacaraController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $totalKasus = FormulirKonsultasi::where('id_user_pengacara', $user->id_user)->count();
        $kasusAktif = FormulirKonsultasi::where('id_user_pengacara', $user->id_user)
            ->where('status_kasus_pengacara', 1)
            ->count();
        $kasusSelesai = FormulirKonsultasi::where('id_user_pengacara', $user->id_user)
            ->where('status_kasus_pengacara', 2)
            ->count();
        $jadwalTemuMendatang = FormulirKonsultasi::where('id_user_pengacara', $user->id_user)
            ->where('status_jadwal_temu', 2)
            ->where('tgl_waktu_jadwal_temu', '>=', now())
            ->count();

        $kasusTerbaru = FormulirKonsultasi::where('id_user_pengacara', $user->id_user)
            ->with(['user', 'klasterisasi', 'kontrak'])
            ->orderBy('id_formulir', 'desc')
            ->limit(5)
            ->get();

        return view('pengacara.dashboard', compact('user', 'totalKasus', 'kasusAktif', 'kasusSelesai', 'jadwalTemuMendatang', 'kasusTerbaru'));
    }

    public function kasusIndex()
    {
        $user = Auth::user();
        $daftarKasus = FormulirKonsultasi::where('id_user_pengacara', $user->id_user)
            ->with(['user', 'klasterisasi', 'kontrak', 'pembayaran'])
            ->orderBy('id_formulir', 'desc')
            ->paginate(10);

        return view('pengacara.kasus_index', compact('daftarKasus'));
    }

    public function kasusDetail($id)
    {
        $user = Auth::user();
        $formulir = FormulirKonsultasi::where('id_user_pengacara', $user->id_user)
            ->with(['user', 'klasterisasi', 'kontrak', 'pembayaran'])
            ->findOrFail($id);

        return view('pengacara.kasus_detail', compact('formulir'));
    }

    public function simpanTtdKonsultan(Request $request, $id)
    {
        $request->validate([
            'signature_data' => 'required|string',
        ]);

        $user = Auth::user();
        $formulir = FormulirKonsultasi::where('id_user_pengacara', $user->id_user)->findOrFail($id);

        if (str_contains($request->signature_data, 'base64,')) {
            $imageParts = explode(';base64,', $request->signature_data);
            $imageBase64 = base64_decode($imageParts[1]);
            $ttdFilename = 'ttd_konsultan_' . $user->id_user . '_' . time() . '.png';
            $dest = public_path('uploads/ttd');
            if (!file_exists($dest)) {
                mkdir($dest, 0777, true);
            }
            file_put_contents($dest . '/' . $ttdFilename, $imageBase64);

            $formulir->ttd_konsultan = $ttdFilename;
            $formulir->save();
        }

        return back()->with('success', 'Tanda tangan digital advokat/konsultan berhasil disimpan pada berkas perkara.');
    }

    public function laporanKasus()
    {
        $user = Auth::user();
        $daftarKasus = FormulirKonsultasi::where('id_user_pengacara', $user->id_user)
            ->with(['user', 'klasterisasi', 'kontrak', 'pembayaran'])
            ->orderBy('id_formulir', 'desc')
            ->get();

        $totalKasus = $daftarKasus->count();
        $kasusSelesai = $daftarKasus->where('status_kasus_pengacara', 2)->count();
        $kasusProgres = $daftarKasus->where('status_kasus_pengacara', 1)->count();

        // Hitung total penerimaan omset & SHU untuk advokat ini
        $formulirIds = $daftarKasus->pluck('id_formulir');
        $omsetKasus = \App\Models\Pembayaran::whereIn('id_formulir', $formulirIds)
            ->where('status', 1)
            ->sum('jumlah_tagihan');

        $feeLangsung = $omsetKasus * 0.60;

        // Dividen pool 15% firma
        $totalFirma = \App\Models\Pembayaran::where('status', 1)->sum('jumlah_tagihan');
        $totalAdvokat = \App\Models\User::where('id_role', 2)->where('is_active', 1)->count();
        $dividenMitra = $totalAdvokat > 0 ? (($totalFirma * 0.15) / $totalAdvokat) : 0;
        $totalShuAdvokat = $feeLangsung + $dividenMitra;

        return view('pengacara.laporan_kasus', compact(
            'user',
            'daftarKasus',
            'totalKasus',
            'kasusSelesai',
            'kasusProgres',
            'omsetKasus',
            'feeLangsung',
            'dividenMitra',
            'totalShuAdvokat'
        ));
    }
}

