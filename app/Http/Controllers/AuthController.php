<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if ($request->has('switch')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        } elseif (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        // default login page (generic)
        return view('auth.login');
    }

    // Role‑specific login pages with distinct content (auto switch if logged in with different role)
    public function showLoginAdmin(Request $request)
    {
        if (Auth::check() && !Auth::user()->isAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        return view('auth.login_admin');
    }

    public function showLoginPengacara(Request $request)
    {
        if (Auth::check() && !Auth::user()->isPengacara()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        return view('auth.login_pengacara');
    }

    public function showLoginKlien(Request $request)
    {
        if (Auth::check() && !Auth::user()->isKlien()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        return view('auth.login_klien');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            if (!$user->is_active) {
                return back()->with('error', 'Akun Anda sedang dinonaktifkan. Silakan hubungi Sekretariat kantor.');
            }

            Auth::login($user, $request->has('remember'));
            $request->session()->regenerate();

            return $this->redirectByRole($user);
        }

        return back()->with('error', 'Username atau Password salah. Silakan coba lagi.')->withInput();
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:128|min:3',
            'username' => 'required|string|max:128|min:3|unique:web_hukum_user,username|alpha_dash',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'username.unique' => 'Username ini sudah digunakan, silakan pilih username lain.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
        ]);

        $user = User::create([
            'id_role' => 3, // Klien / Pemohon
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'image' => 'default.jpg',
            'is_active' => 1,
            'date_created' => time(),
        ]);

        Auth::login($user);

        return redirect()->route('klien.dashboard')->with('success', 'Selamat datang! Pendaftaran akun berhasil.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $to = $request->query('to');
        if ($to === 'pengacara') {
            return redirect()->route('login.pengacara')->with('success', 'Silakan pilih akun Advokat / Pengacara.');
        } elseif ($to === 'admin') {
            return redirect()->route('login.admin')->with('success', 'Silakan pilih akun Sekretariat / Admin.');
        } elseif ($to === 'klien') {
            return redirect()->route('login.klien')->with('success', 'Silakan pilih akun Klien / Pemohon.');
        }

        return redirect()->route('login', ['switch' => 1])->with('success', 'Anda telah berhasil keluar. Silakan pilih akun untuk masuk kembali.');
    }

    private function redirectByRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isPengacara()) {
            return redirect()->route('pengacara.dashboard');
        } else {
            return redirect()->route('klien.dashboard');
        }
    }
}

