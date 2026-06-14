<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Pengajar\PengajarDashboardController;
use App\Http\Controllers\Pengajar\PengajarKelasController;
use App\Http\Controllers\Pengajar\PengajarProfileController;
use App\Http\Controllers\Pengajar\PengajarKuisController;
use App\Http\Controllers\Mahasiswa\MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\MahasiswaKelasController;
use App\Http\Controllers\Mahasiswa\MahasiswaBeliController;
use App\Http\Controllers\Mahasiswa\MahasiswaProfileController;
use App\Http\Controllers\Mahasiswa\DeviceManagementController;
use App\Http\Controllers\Mahasiswa\MahasiswaKuisController;
use App\Http\Controllers\Mahasiswa\MahasiswaTransaksiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/* ─── Public Routes ─── */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/bantuan', [HelpController::class, 'index'])->name('help');
Route::post('/bantuan/email', [HelpController::class, 'sendEmail'])->name('help.email');

/* ─── Auth Routes (Guest Only) ─── */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle.login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

/* ─── Logout ─── */
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/* ─── Email Verification ─── */
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [AuthController::class, 'verifyEmail'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        $user = $request->user();
        return redirect($user->role === 'mahasiswa' ? '/mahasiswa/dashboard' : '/')->with('success', 'Email berhasil diverifikasi!');
    })->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])
        ->middleware('throttle:6,1')->name('verification.send');
});

/* ─── Midtrans Payment Webhook (No Auth, No CSRF) ─── */
Route::post('/payment/notification', [PaymentController::class, 'notification'])
    ->name('payment.notification');

/* ─── Admin Routes ─── */
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/mahasiswa', [AdminController::class, 'mahasiswa'])->name('admin.mahasiswa');
    Route::get('/pengajar', [AdminController::class, 'pengajar'])->name('admin.pengajar');
    Route::post('/pengajar', [AdminController::class, 'storePengajar'])->name('admin.pengajar.store');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

    // Change Password
    Route::put('/users/{user}/password', [AdminController::class, 'changePassword'])->name('admin.users.password');

    // Export PDF
    Route::get('/mahasiswa/export-pdf', [AdminController::class, 'exportMahasiswaPdf'])->name('admin.mahasiswa.pdf');
    Route::get('/pengajar/export-pdf', [AdminController::class, 'exportPengajarPdf'])->name('admin.pengajar.pdf');

    // Berikan Kelas Gratis
    Route::post('/grant-free-class', [AdminController::class, 'grantFreeClass'])->name('admin.grant-free-class');

    // Riwayat Transaksi
    Route::get('/transaksi', [AdminController::class, 'transaksi'])->name('admin.transaksi');
});

/* ─── Pengajar Routes ─── */
Route::prefix('pengajar')->middleware(['auth', 'role:pengajar'])->group(function () {
    Route::get('/dashboard', [PengajarDashboardController::class, 'index'])->name('pengajar.dashboard');

    // Kelas Management
    Route::get('/kelas', [PengajarKelasController::class, 'index'])->name('pengajar.kelas.index');
    Route::get('/kelas/create', [PengajarKelasController::class, 'create'])->name('pengajar.kelas.create');
    Route::post('/kelas', [PengajarKelasController::class, 'store'])->name('pengajar.kelas.store');
    Route::get('/kelas/{kela}', [PengajarKelasController::class, 'show'])->name('pengajar.kelas.show');
    Route::put('/kelas/{kela}', [PengajarKelasController::class, 'update'])->name('pengajar.kelas.update');
    Route::delete('/kelas/{kela}', [PengajarKelasController::class, 'destroy'])->name('pengajar.kelas.destroy');

    // Alias for sidebar "Kelas Saya" link
    Route::get('/kelas-saya', [PengajarKelasController::class, 'index'])->name('pengajar.kelas-saya');

    // Pertemuan & Tugas
    Route::post('/kelas/{kela}/pertemuan', [PengajarKelasController::class, 'storePertemuan'])->name('pengajar.pertemuan.store');
    Route::get('/pertemuan/{pertemuan}', [PengajarKelasController::class, 'showPertemuan'])->name('pengajar.pertemuan.show');
    Route::delete('/pertemuan/{pertemuan}', [PengajarKelasController::class, 'destroyPertemuan'])->name('pengajar.pertemuan.destroy');

    // Absensi & Nilai
    Route::post('/pertemuan/{pertemuan}/absensi', [PengajarKelasController::class, 'storeAbsensi'])->name('pengajar.absensi.store');
    Route::post('/pertemuan/{pertemuan}/nilai', [PengajarKelasController::class, 'nilaiTugas'])->name('pengajar.nilai.store');

    // Export PDF Rekap Absensi
    Route::get('/kelas/{kela}/absensi-pdf', [PengajarKelasController::class, 'exportAbsensiPdf'])->name('pengajar.absensi.pdf');

    // Kuis
    Route::get('/kelas/{kela}/kuis/create', [PengajarKuisController::class, 'create'])->name('pengajar.kuis.create');
    Route::post('/kelas/{kela}/kuis', [PengajarKuisController::class, 'store'])->name('pengajar.kuis.store');
    Route::get('/kuis/{kui}', [PengajarKuisController::class, 'show'])->name('pengajar.kuis.show');
    Route::post('/kuis/{kui}/nilai-essay', [PengajarKuisController::class, 'nilaiEssay'])->name('pengajar.kuis.nilai-essay');
    Route::put('/kuis/{kui}/toggle', [PengajarKuisController::class, 'toggleActive'])->name('pengajar.kuis.toggle');
    Route::delete('/kuis/{kui}', [PengajarKuisController::class, 'destroy'])->name('pengajar.kuis.destroy');

    // Profile
    Route::get('/profile', [PengajarProfileController::class, 'index'])->name('pengajar.profile');
    Route::put('/profile', [PengajarProfileController::class, 'update'])->name('pengajar.profile.update');
});

/* ─── Mahasiswa Routes ─── */
Route::prefix('mahasiswa')->middleware(['auth', 'role:mahasiswa', 'device.limit'])->group(function () {
    Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('mahasiswa.dashboard');

    // Kelas Saya
    Route::get('/kelas', [MahasiswaKelasController::class, 'index'])->name('mahasiswa.kelas.index');
    Route::get('/kelas/{kela}', [MahasiswaKelasController::class, 'show'])->name('mahasiswa.kelas.show');
    Route::get('/pertemuan/{pertemuan}', [MahasiswaKelasController::class, 'showPertemuan'])->name('mahasiswa.pertemuan.show');
    Route::post('/tugas/{pertemuan}/submit', [MahasiswaKelasController::class, 'submitTugas'])->name('mahasiswa.tugas.submit');

    // Kuis
    Route::get('/kuis/{kui}', [MahasiswaKuisController::class, 'show'])->name('mahasiswa.kuis.show');
    Route::post('/kuis/{kui}/submit', [MahasiswaKuisController::class, 'submit'])->name('mahasiswa.kuis.submit');
    Route::get('/kuis/{kui}/hasil', [MahasiswaKuisController::class, 'hasil'])->name('mahasiswa.kuis.hasil');

    // Beli Kelas
    Route::get('/beli-kelas', [MahasiswaBeliController::class, 'index'])->name('mahasiswa.beli-kelas');
    Route::get('/beli-kelas/{kela}/checkout', [MahasiswaBeliController::class, 'checkout'])->name('mahasiswa.checkout');
    Route::post('/beli-kelas/{kela}/create-transaction', [MahasiswaBeliController::class, 'createTransaction'])->name('mahasiswa.create-transaction');
    Route::get('/beli-kelas/{kela}/finish', [MahasiswaBeliController::class, 'paymentFinish'])->name('mahasiswa.payment-finish');

    // Riwayat Transaksi
    Route::get('/transaksi', [MahasiswaTransaksiController::class, 'index'])->name('mahasiswa.transaksi');

    // Device Management
    Route::get('/devices', [DeviceManagementController::class, 'index'])->name('mahasiswa.devices');
    Route::delete('/devices/{sessionId}', [DeviceManagementController::class, 'destroy'])->name('mahasiswa.devices.destroy');

    // Profile
    Route::get('/profile', [MahasiswaProfileController::class, 'index'])->name('mahasiswa.profile');
    Route::put('/profile', [MahasiswaProfileController::class, 'update'])->name('mahasiswa.profile.update');
});
