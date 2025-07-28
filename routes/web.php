<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KodeMaterialController;
use App\Http\Controllers\UomController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\RevisiController;
use App\Http\Controllers\BillOfMaterialController;

// ==========================
// 🔐 AUTH ROUTES
// ==========================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Form Lupa Password (input NIP)
Route::get('/password/reset', [AuthController::class, 'showForgotPassword'])->name('password.request');

// Form Reset Password Manual (input NIP + password baru)
Route::get('/password/reset/form', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update');

// ==========================
// 🏠 DASHBOARD & PROFILE
// ==========================

Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

Route::prefix('profile')->group(function () {
    Route::get('/', fn() => view('profile'))->name('profile');
    Route::get('/edit', fn() => view('profile.edit'))->name('profile.edit');
});

// ==========================
// 📦 MASTER DATA ROUTES
// ==========================

Route::prefix('master')->group(function () {

    Route::prefix('kode-material')->group(function () {
        Route::get('/', [KodeMaterialController::class, 'index'])->name('kode-material.index');
        Route::get('/data', [KodeMaterialController::class, 'getData'])->name('kode-material.getData');
        Route::post('/', [KodeMaterialController::class, 'store'])->name('kode-material.store');
        Route::get('/{kodeMaterial}', [KodeMaterialController::class, 'show'])->name('kode-material.show');
        Route::put('/{kodeMaterial}', [KodeMaterialController::class, 'update'])->name('kode-material.update');
        Route::delete('/{kodeMaterial}', [KodeMaterialController::class, 'destroy'])->name('kode-material.destroy');
    });

    Route::prefix('uom')->group(function () {
        Route::get('/', [UomController::class, 'index'])->name('uom.index');
        Route::get('/data', [UomController::class, 'getData'])->name('uom.getData');
        Route::post('/', [UomController::class, 'store'])->name('uom.store');
        Route::get('/{uom}', [UomController::class, 'show'])->name('uom.show');
        Route::put('/{uom}', [UomController::class, 'update'])->name('uom.update');
        Route::delete('/{uom}', [UomController::class, 'destroy'])->name('uom.destroy');
    });

    Route::prefix('proyek')->group(function () {
        Route::get('/', [ProyekController::class, 'index'])->name('proyek.index');
        Route::get('/data', [ProyekController::class, 'getData'])->name('proyek.getData');
        Route::post('/', [ProyekController::class, 'store'])->name('proyek.store');
        Route::get('/{proyek}', [ProyekController::class, 'show'])->name('proyek.show');
        Route::put('/{proyek}', [ProyekController::class, 'update'])->name('proyek.update');
        Route::delete('/{proyek}', [ProyekController::class, 'destroy'])->name('proyek.destroy');
    });

    Route::prefix('revisi')->group(function () {
        Route::get('/', [RevisiController::class, 'index'])->name('revisi.index');
        Route::get('/data', [RevisiController::class, 'getData'])->name('revisi.getData');
        Route::post('/', [RevisiController::class, 'store'])->name('revisi.store');
        Route::put('/{revisi}', [RevisiController::class, 'update'])->name('revisi.update');
        Route::delete('/{revisi}', [RevisiController::class, 'destroy'])->name('revisi.destroy');
    });

});

// ==========================
// ⚙️ BOM
// ==========================
Route::prefix('bom')->group(function () {
    Route::get('/', [BillOfMaterialController::class, 'index'])->name('bom.index');
    Route::get('/create', [BillOfMaterialController::class, 'create'])->name('bom.create');
    Route::post('/', [BillOfMaterialController::class, 'store'])->name('bom.store');
    Route::get('/{id}', [BillOfMaterialController::class, 'show'])->name('bom.show');
    Route::get('/{id}/edit', [BillOfMaterialController::class, 'edit'])->name('bom.edit');
    Route::put('/{id}', [BillOfMaterialController::class, 'update'])->name('bom.update');
    Route::delete('/{id}', [BillOfMaterialController::class, 'destroy'])->name('bom.destroy');
});

// ==========================
// 👥 USER
// ==========================
Route::view('/user', 'user.user')->name('user');
Route::view('/user-group', 'user.user-group')->name('user.group');
<<<<<<< Updated upstream
=======


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
>>>>>>> Stashed changes
