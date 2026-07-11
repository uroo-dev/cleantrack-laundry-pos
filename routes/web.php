<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Staff\OrderController;
use App\Http\Controllers\Staff\TrackingController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\Customer\TrackingController as CustomerTracking;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/tracking/{kode?}', [PublicController::class, 'tracking'])->name('tracking');
Route::get('/tracking/download/{kode}', [PublicController::class, 'downloadNota'])->name('tracking.download');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('pelanggan', PelangganController::class)->except('show');
    Route::resource('layanan', LayananController::class);
    Route::resource('transaksi', TransaksiController::class)->only(['index', 'show']);
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pendapatan', [LaporanController::class, 'pendapatan'])->name('laporan.pendapatan');
    Route::get('/laporan/pelanggan', [LaporanController::class, 'pelanggan'])->name('laporan.pelanggan');
    Route::get('/laporan/layanan', [LaporanController::class, 'layanan'])->name('laporan.layanan');
    Route::resource('pegawai', PegawaiController::class);
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
});

Route::prefix('staff')->name('staff.')->middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');
    Route::get('/order/create-pelanggan', [OrderController::class, 'createPelanggan'])->name('order.create-pelanggan');
    Route::get('/order/get-pelanggan', [OrderController::class, 'getPelanggan'])->name('order.get-pelanggan');
    Route::get('/order/queue', [OrderController::class, 'queue'])->name('order.queue');
    Route::put('/order/{id}/status', [OrderController::class, 'updateStatus'])->name('order.update-status');
    Route::get('/order/{id}/nota', [OrderController::class, 'cetakNota'])->name('order.nota');
    Route::get('/order/{id}/print', [OrderController::class, 'printNota'])->name('order.print');
    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');
    Route::put('/tracking/{id}/progres', [TrackingController::class, 'updateProgres'])->name('tracking.progres');
    Route::get('/tracking/{id}/estimasi', [TrackingController::class, 'getEstimasi'])->name('tracking.estimasi');
});

Route::prefix('pelanggan')->name('customer.')->middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');
    Route::post('/tracking', [CustomerTracking::class, 'cekStatus'])->name('tracking.cek');
    Route::get('/riwayat', [CustomerTracking::class, 'riwayat'])->name('riwayat');
    Route::post('/rate', [CustomerTracking::class, 'rate'])->name('rate');
});
