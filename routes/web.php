<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KlienController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\PengacaraController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Separate login pages per role
Route::get('/login', [AuthController::class, 'showLogin'])->name('login'); // generic fallback
Route::get('/login/admin', [AuthController::class, 'showLoginAdmin'])->name('login.admin');
Route::get('/login/pengacara', [AuthController::class, 'showLoginPengacara'])->name('login.pengacara');
Route::get('/login/klien', [AuthController::class, 'showLoginKlien'])->name('login.klien');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Payment Gateway Callback, Token & QRIS Simulator
Route::post('/payment/notification', [PaymentGatewayController::class, 'handleNotification'])->name('payment.notification');
Route::get('/payment/snap-token/{id}', [PaymentGatewayController::class, 'getSnapToken'])->name('payment.snap_token');
Route::get('/payment/qris-data/{id}', [PaymentGatewayController::class, 'getQrisData'])->name('payment.qris_data');
Route::get('/payment/gopay-qris/{id}', [PaymentGatewayController::class, 'chargeGopayQris'])->name('payment.gopay_qris');
Route::get('/payment/shopeepay/{id}', [PaymentGatewayController::class, 'chargeShopeePay'])->name('payment.shopeepay');
Route::get('/payment/check-status/{id}', [PaymentGatewayController::class, 'checkStatus'])->name('payment.check_status');
Route::get('/payment/va-data/{id}/{bank}', [PaymentGatewayController::class, 'getVaData'])->name('payment.va_data');
Route::post('/payment/simulate/{id}', [PaymentGatewayController::class, 'simulateSuccess'])->name('payment.simulate');
Route::get('/payment/simulator/qris/{id}', [PaymentGatewayController::class, 'simulatorQrisView'])->name('payment.simulator_qris');
Route::post('/payment/simulator/qris/{id}/pay', [PaymentGatewayController::class, 'simulatorQrisPay'])->name('payment.simulator_qris_pay');

// Shared Calendar Events JSON
Route::get('/jadwal/events', [JadwalController::class, 'getEventsJson'])->middleware('auth')->name('jadwal.events');

// Client Protected Routes (Role: 3)
Route::middleware(['auth', 'role:klien'])->prefix('klien')->name('klien.')->group(function () {
    Route::get('/dashboard', [KlienController::class, 'dashboard'])->name('dashboard');
    Route::get('/konsultasi', [KlienController::class, 'konsultasiIndex'])->name('konsultasi.index');
    Route::get('/konsultasi/baru', [KlienController::class, 'konsultasiCreate'])->name('konsultasi.create');
    Route::post('/konsultasi/simpan', [KlienController::class, 'konsultasiStore'])->name('konsultasi.store');
    Route::get('/konsultasi/{id}', [KlienController::class, 'konsultasiDetail'])->name('konsultasi.detail');

    Route::get('/jadwal', [JadwalController::class, 'indexKlien'])->name('jadwal.index');

    Route::get('/chat/{id_formulir?}', [KlienController::class, 'chatIndex'])->name('chat.index');
    Route::post('/chat/send', [KlienController::class, 'chatSend'])->name('chat.send');
    Route::get('/chat/fetch/{id_formulir}', [KlienController::class, 'chatFetch'])->name('chat.fetch');

    Route::get('/pembayaran', [KlienController::class, 'pembayaranIndex'])->name('pembayaran.index');
});

// Admin / Sekretaris Protected Routes (Role: 1)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/konsultasi', [AdminController::class, 'konsultasiIndex'])->name('konsultasi.index');
    Route::get('/konsultasi/{id}', [AdminController::class, 'konsultasiDetail'])->name('konsultasi.detail');
    Route::post('/konsultasi/{id}/verifikasi', [AdminController::class, 'konsultasiVerifikasi'])->name('konsultasi.verifikasi');
    Route::post('/konsultasi/{id}/jadwal', [AdminController::class, 'jadwalTemuStore'])->name('konsultasi.jadwal');
    Route::post('/konsultasi/{id}/progres', [AdminController::class, 'progresUpdate'])->name('konsultasi.progres');
    Route::post('/konsultasi/{id}/kontrak', [AdminController::class, 'kontrakUpload'])->name('konsultasi.kontrak');

    Route::get('/jadwal', [JadwalController::class, 'indexAdmin'])->name('jadwal.index');
    Route::post('/jadwal/{id}/update', [JadwalController::class, 'updateJadwal'])->name('jadwal.update');

    Route::get('/pembayaran', [AdminController::class, 'pembayaranIndex'])->name('pembayaran.index');
    Route::post('/pembayaran/store', [AdminController::class, 'pembayaranStore'])->name('pembayaran.store');

    Route::get('/chat/{id_formulir?}', [AdminController::class, 'chatIndex'])->name('chat.index');
    Route::post('/chat/send', [AdminController::class, 'chatSend'])->name('chat.send');

    Route::get('/laporan', [AdminController::class, 'laporanIndex'])->name('laporan.index');

    Route::get('/users', [AdminController::class, 'userIndex'])->name('users.index');
    Route::post('/users/store', [AdminController::class, 'userStore'])->name('users.store');
    Route::post('/users/{id}/toggle', [AdminController::class, 'userToggleStatus'])->name('users.toggle');
});

// Pengacara / Advokat Protected Routes (Role: 2)
Route::middleware(['auth', 'role:pengacara'])->prefix('pengacara')->name('pengacara.')->group(function () {
    Route::get('/dashboard', [PengacaraController::class, 'dashboard'])->name('dashboard');
    Route::get('/kasus', [PengacaraController::class, 'kasusIndex'])->name('kasus.index');
    Route::get('/kasus/{id}', [PengacaraController::class, 'kasusDetail'])->name('kasus.detail');
    Route::post('/kasus/{id}/ttd', [PengacaraController::class, 'simpanTtdKonsultan'])->name('kasus.ttd');
    Route::get('/jadwal', [JadwalController::class, 'indexPengacara'])->name('jadwal.index');
    Route::get('/laporan', [PengacaraController::class, 'laporanKasus'])->name('laporan.index');
});
