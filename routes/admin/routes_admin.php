<?php
// ============================================================
// TAMBAHKAN ke routes/web.php
// ============================================================

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JadwalController    as AdminJadwal;
use App\Http\Controllers\Admin\PemesananController as AdminPemesanan;
use App\Http\Controllers\Admin\DriverController    as AdminDriver;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/',          [DashboardController::class, 'index'])->name('dashboard');

    // Jadwal
    Route::get   ('/jadwal',         [AdminJadwal::class, 'index'])->name('jadwal.index');
    Route::post  ('/jadwal',         [AdminJadwal::class, 'store'])->name('jadwal.store');
    Route::put   ('/jadwal/{jadwal}',[AdminJadwal::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{jadwal}',[AdminJadwal::class, 'destroy'])->name('jadwal.destroy');
    Route::post  ('/jadwal/massal',  [AdminJadwal::class, 'buatMassal'])->name('jadwal.massal');

    // Pemesanan
    Route::get ('/pemesanan',              [AdminPemesanan::class, 'index'])->name('pemesanan.index');
    Route::get ('/pemesanan/cetak-harian', [AdminPemesanan::class, 'cetakHarian'])->name('pemesanan.cetak-harian');
    Route::get ('/pemesanan/{pemesanan}',  [AdminPemesanan::class, 'show'])->name('pemesanan.show');
    Route::post('/pemesanan/{pemesanan}/status', [AdminPemesanan::class, 'updateStatus'])->name('pemesanan.update-status');
    Route::post('/pemesanan/share-driver', [AdminPemesanan::class, 'shareDriver'])->name('pemesanan.share-driver');

    // Driver
    Route::get   ('/driver',          [AdminDriver::class, 'index'])->name('driver.index');
    Route::post  ('/driver',          [AdminDriver::class, 'store'])->name('driver.store');
    Route::put   ('/driver/{driver}', [AdminDriver::class, 'update'])->name('driver.update');
    Route::delete('/driver/{driver}', [AdminDriver::class, 'destroy'])->name('driver.destroy');
});


// ============================================================
// TAMBAHKAN ke bootstrap/app.php (Laravel 13) — daftarkan middleware
// ============================================================
/*
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\IsAdmin::class,
    ]);
})
*/


// ============================================================
// SEEDER — buat akun admin pertama
// Jalankan: php artisan tinker
// Lalu paste kode di bawah ini:
// ============================================================
/*
\App\Models\User::create([
    'nama_lengkap'  => 'Admin GOTRAV',
    'no_whatsapp'   => '08100000000',
    'jenis_kelamin' => 'laki-laki',
    'role'          => 'admin',
    'password'      => bcrypt('admin123'),
]);
*/
