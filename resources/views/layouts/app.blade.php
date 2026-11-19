<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Layanan & Konsultasi Hukum') - Handoko & Partners Law Firm</title>

    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Signature Pad & SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Midtrans Snap Sandbox JS -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-SIMULATOR_AHMAD_HANDOKO_2026') }}"></script>

    <style>
        :root {
            --law-navy: #0A2540;
            --law-navy-dark: #061727;
            --law-gold: #C5A880;
            --law-gold-hover: #b39266;
            --law-bg: #F8FAFC;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--law-bg);
            color: #1E293B;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-law {
            background-color: var(--law-navy);
            border-bottom: 2px solid var(--law-gold);
        }
        .navbar-brand {
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #FFFFFF !important;
        }
        .navbar-brand span {
            color: var(--law-gold);
        }
        .nav-link {
            color: #E2E8F0 !important;
            font-weight: 500;
            padding: 8px 16px !important;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        .nav-link:hover, .nav-link.active {
            color: #FFFFFF !important;
            background: rgba(197, 168, 128, 0.2);
        }
        .btn-gold {
            background-color: var(--law-gold);
            color: #0A2540;
            font-weight: 700;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-gold:hover {
            background-color: var(--law-gold-hover);
            color: #061727;
        }
        .btn-navy {
            background-color: var(--law-navy);
            color: #FFFFFF;
            font-weight: 600;
        }
        .btn-navy:hover {
            background-color: var(--law-navy-dark);
            color: var(--law-gold);
        }
        .card-custom {
            border: none;
            border-radius: 12px;
            background: #FFFFFF;
            box-shadow: 0 4px 12px rgba(10, 37, 64, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .badge-role {
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
        }
        .timeline-step {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            margin: 20px 0;
        }
        .timeline-step::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 3px;
            background: #E2E8F0;
            z-index: 1;
        }
        .step-item {
            position: relative;
            z-index: 2;
            background: #FFFFFF;
            padding: 0 10px;
            text-align: center;
        }
        .step-circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin: 0 auto 8px;
            background: #E2E8F0;
            color: #64748B;
        }
        .step-item.active .step-circle {
            background: var(--law-navy);
            color: var(--law-gold);
            border: 2px solid var(--law-gold);
        }
        .step-item.completed .step-circle {
            background: #10B981;
            color: #FFFFFF;
        }
        .footer-law {
            background: var(--law-navy-dark);
            color: #94A3B8;
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-law sticky-top shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fa-solid fa-scale-balanced me-2"></i>HANDOKO & PARTNERS <span>LAW FIRM</span>
            </a>
            <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <i class="fa-solid fa-bars fs-4"></i>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="fa-solid fa-house me-1"></i> Beranda
                        </a>
                    </li>

                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/#layanan') }}">Layanan Hukum</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/#advokat') }}">Tim Advokat</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/#profil') }}">Tentang Kami</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/#kontak') }}">Kontak</a>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-outline-light px-3 py-1 me-2" href="{{ route('login') }}">
                                <i class="fa-solid fa-right-to-bracket me-1"></i> Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-gold px-3 py-1" href="{{ route('register') }}">
                                <i class="fa-solid fa-user-plus me-1"></i> Registrasi Klien
                            </a>
                        </li>
                    @else
                        @if(Auth::user()->isAdmin())
                            <!-- Menu Sekretaris / Admin -->
                            <li class="nav-item"><a class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-chart-pie me-1"></i> Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->is('admin/konsultasi*') ? 'active' : '' }}" href="{{ route('admin.konsultasi.index') }}"><i class="fa-solid fa-folder-open me-1"></i> Perkara & Konsultasi</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->is('admin/jadwal*') ? 'active' : '' }}" href="{{ route('admin.jadwal.index') }}"><i class="fa-solid fa-calendar-days me-1"></i> Kalender Agenda</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->is('admin/pembayaran*') ? 'active' : '' }}" href="{{ route('admin.pembayaran.index') }}"><i class="fa-solid fa-credit-card me-1"></i> Tagihan</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->is('admin/chat*') ? 'active' : '' }}" href="{{ route('admin.chat.index') }}"><i class="fa-solid fa-comments me-1"></i> Chat Klien</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->is('admin/laporan*') ? 'active' : '' }}" href="{{ route('admin.laporan.index') }}"><i class="fa-solid fa-chart-line me-1"></i> Statistik & SHU</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"><i class="fa-solid fa-users-gear me-1"></i> Pengguna</a></li>
                        @elseif(Auth::user()->isPengacara())
                            <!-- Menu Pengacara -->
                            <li class="nav-item"><a class="nav-link {{ request()->is('pengacara/dashboard*') ? 'active' : '' }}" href="{{ route('pengacara.dashboard') }}"><i class="fa-solid fa-chart-line me-1"></i> Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->is('pengacara/kasus*') ? 'active' : '' }}" href="{{ route('pengacara.kasus.index') }}"><i class="fa-solid fa-briefcase me-1"></i> Kasus Ditangani</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->is('pengacara/jadwal*') ? 'active' : '' }}" href="{{ route('pengacara.jadwal.index') }}"><i class="fa-solid fa-calendar-days me-1"></i> Agenda Sidang/Temu</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->is('pengacara/laporan*') ? 'active' : '' }}" href="{{ route('pengacara.laporan.index') }}"><i class="fa-solid fa-file-invoice-dollar me-1"></i> Rekap Kasus & SHU</a></li>
                        @else
                            <!-- Menu Klien -->
                            <li class="nav-item"><a class="nav-link {{ request()->is('klien/dashboard*') ? 'active' : '' }}" href="{{ route('klien.dashboard') }}"><i class="fa-solid fa-gauge me-1"></i> Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->is('klien/konsultasi*') ? 'active' : '' }}" href="{{ route('klien.konsultasi.index') }}"><i class="fa-solid fa-file-circle-plus me-1"></i> Permohonan Saya</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->is('klien/jadwal*') ? 'active' : '' }}" href="{{ route('klien.jadwal.index') }}"><i class="fa-solid fa-calendar-check me-1"></i> Jadwal Temu</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->is('klien/chat*') ? 'active' : '' }}" href="{{ route('klien.chat.index') }}"><i class="fa-solid fa-comments me-1"></i> Konsultasi Chat</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->is('klien/pembayaran*') ? 'active' : '' }}" href="{{ route('klien.pembayaran.index') }}"><i class="fa-solid fa-wallet me-1"></i> Pembayaran</a></li>
                        @endif

                        <li class="nav-item dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <span class="badge bg-warning text-dark me-2">{{ Auth::user()->role->nama_role ?? 'User' }}</span>
                                <span class="fw-bold">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li class="dropdown-header text-muted">Akun Aktif: <strong>{{ Auth::user()->username }}</strong></li>
                                <li><hr class="dropdown-divider"></li>
                                <li class="dropdown-header small text-uppercase fw-bold text-navy">Ganti Peran / Akun:</li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('logout', ['to' => 'pengacara']) }}">
                                        <i class="fa-solid fa-scale-balanced text-success me-2"></i> Masuk sbg <strong>Advokat</strong>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('logout', ['to' => 'admin']) }}">
                                        <i class="fa-solid fa-user-shield text-primary me-2"></i> Masuk sbg <strong>Admin</strong>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('logout', ['to' => 'klien']) }}">
                                        <i class="fa-solid fa-user text-warning me-2"></i> Masuk sbg <strong>Klien</strong>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger py-2 fw-semibold" href="{{ route('logout') }}">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i> Keluar (Logout)
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <a href="{{ route('logout') }}" class="btn btn-sm btn-outline-danger d-flex align-items-center mt-2 mt-lg-0 px-3 shadow-sm" title="Keluar dari akun saat ini">
                                <i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Keluar
                            </a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Notification Alerts -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(isset($errors) && $errors->any())
            <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> <strong>Periksa data masukan Anda:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-law py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    <p class="mb-0 text-white fw-bold">HANDOKO & PARTNERS LAW FIRM BANDAR LAMPUNG</p>
                    <small>Kantor Advokat, Kurator & Konsultan Hukum &bull; Sistem Informasi Terintegrasi &copy; {{ date('Y') }}.</small>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <small>Didukung oleh Laravel 12 & MySQL &bull; Terintegrasi Midtrans Payment Gateway</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>

