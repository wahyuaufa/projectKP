<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JadwalController    as AdminJadwal;
use App\Http\Controllers\Admin\PemesananController as AdminPemesanan;
use App\Http\Controllers\Admin\DriverController    as AdminDriver;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Jadwal
    Route::get   ('/jadwal',          [AdminJadwal::class, 'index'])->name('jadwal.index');
    Route::post  ('/jadwal',          [AdminJadwal::class, 'store'])->name('jadwal.store');
    Route::put   ('/jadwal/{jadwal}', [AdminJadwal::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{jadwal}', [AdminJadwal::class, 'destroy'])->name('jadwal.destroy');
    Route::post  ('/jadwal/massal',   [AdminJadwal::class, 'buatMassal'])->name('jadwal.massal');

    // Pemesanan
    Route::get ('/pemesanan',                     [AdminPemesanan::class, 'index'])->name('pemesanan.index');
    Route::get ('/pemesanan/cetak-harian',        [AdminPemesanan::class, 'cetakHarian'])->name('pemesanan.cetak-harian');
    Route::get ('/pemesanan/{pemesanan}',         [AdminPemesanan::class, 'show'])->name('pemesanan.show');
    Route::post('/pemesanan/{pemesanan}/status',  [AdminPemesanan::class, 'updateStatus'])->name('pemesanan.update-status');
    Route::post('/pemesanan/share-driver',        [AdminPemesanan::class, 'shareDriver'])->name('pemesanan.share-driver');

    // Driver
    Route::get   ('/driver',           [AdminDriver::class, 'index'])->name('driver.index');
    Route::post  ('/driver',           [AdminDriver::class, 'store'])->name('driver.store');
    Route::put   ('/driver/{driver}',  [AdminDriver::class, 'update'])->name('driver.update');
    Route::delete('/driver/{driver}',  [AdminDriver::class, 'destroy'])->name('driver.destroy');
});

// ── Wilayah API (public, untuk AJAX) ─────────────────────────
Route::prefix('api/wilayah')->name('wilayah.')->group(function () {
    Route::get('/cek-jadwal', [WilayahController::class, 'cekJadwal'])->name('cek-jadwal');
    Route::get('/provinces',              [WilayahController::class, 'provinces'])->name('provinces');
    Route::get('/regencies/{provinceId}', [WilayahController::class, 'regencies'])->name('regencies');
    Route::get('/districts/{regencyId}',  [WilayahController::class, 'districts'])->name('districts');
    Route::get('/villages/{districtId}',  [WilayahController::class, 'villages'])->name('villages');
});

// ── Public ────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// ── Auth ──────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/daftar',   [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar',  [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Booking Flow ──────────────────────────────────────────────
Route::prefix('booking')->name('booking.')->middleware('auth')->group(function () {
    Route::get('/',             [BookingController::class, 'pilihArmada'])->name('armada');
    Route::post('/armada',      [BookingController::class, 'simpanArmada'])->name('simpan-armada');
    Route::get('/rute-jadwal',  [BookingController::class, 'ruteJadwal'])->name('rute-jadwal');
    Route::post('/rute-jadwal', [BookingController::class, 'simpanRuteJadwal'])->name('simpan-rute-jadwal');
    Route::get('/kursi',        [BookingController::class, 'pilihKursi'])->name('kursi');
    Route::post('/kursi',       [BookingController::class, 'simpanKursi'])->name('simpan-kursi');
    Route::get('/detail',       [BookingController::class, 'detailPemesanan'])->name('detail');
    Route::post('/detail',      [BookingController::class, 'simpanDetail'])->name('simpan-detail');
    Route::get('/konfirmasi',   [BookingController::class, 'konfirmasi'])->name('konfirmasi');
    Route::post('/proses',      [BookingController::class, 'prosesPemesanan'])->name('proses');

    // AJAX
    Route::get('/get-jadwal',   [BookingController::class, 'getJadwal'])->name('get-jadwal');
});

// ── Riwayat ───────────────────────────────────────────────────
Route::prefix('riwayat')->name('riwayat.')->middleware('auth')->group(function () {
    Route::get('/',                    [RiwayatController::class, 'index'])->name('index');
    Route::get('/tiket/{kode}',        [RiwayatController::class, 'tiket'])->name('tiket');
    Route::post('/batalkan/{kode}',    [RiwayatController::class, 'batalkan'])->name('batalkan');
});
