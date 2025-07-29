<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KodeMaterialController;
use App\Http\Controllers\UomController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\RevisiController;
use App\Http\Controllers\BillOfMaterialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserGroupController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\DashboardController;

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
// 🔒 PROTECTED ROUTES
// ==========================
Route::middleware(['auth', 'permission'])->group(function () {

    // ==========================
    // 🏠 DASHBOARD - SEKARANG HARUS ADA PERMISSION
    // ==========================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // PROFILE ROUTES - Updated dengan controller methods (TETAP PUBLIC)
    Route::prefix('profile')->group(function () {
        Route::get('/', [UserController::class, 'profile'])->name('profile');
        Route::get('/edit', [UserController::class, 'editProfile'])->name('profile.edit');
        Route::put('/', [UserController::class, 'updateProfile'])->name('profile.update');
    });

    // ==========================
    // 🛆 MASTER DATA ROUTES
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
            Route::get('/{revisi}', [RevisiController::class, 'show'])->name('revisi.show');
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
    // 👥 USER MANAGEMENT
    // ==========================
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/data', [UserController::class, 'getData'])->name('getData');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::get('/user-group', [UserGroupController::class, 'index'])->name('user.group');
    Route::prefix('user-group')->name('user.group.')->group(function () {
        Route::get('/data', [UserGroupController::class, 'getData'])->name('getData');
        Route::post('/', [UserGroupController::class, 'store'])->name('store');
        Route::get('/{id}', [UserGroupController::class, 'show'])->name('show');
        Route::put('/{id}', [UserGroupController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserGroupController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/permissions', [UserGroupController::class, 'getPermissions'])->name('permissions');
    });

    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/data', [PermissionController::class, 'getData'])->name('getData');
        Route::post('/', [PermissionController::class, 'store'])->name('store');
        Route::get('/{id}', [PermissionController::class, 'show'])->name('show');
        Route::put('/{id}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('destroy');
    });

});

// ==========================
// 🔄 REDIRECTS - PERBAIKAN UTAMA
// ==========================

// Route root - redirect ke halaman yang user bisa akses
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    
    $user = Auth::user();
    
    // Jika superadmin, langsung ke dashboard
    if ($user->isSuperAdmin()) {
        return redirect()->route('dashboard');
    }
    
    // Cari route pertama yang bisa diakses
    $firstAccessibleRoute = $user->getFirstAccessibleRoute();
    
    if ($firstAccessibleRoute) {
        return redirect()->route($firstAccessibleRoute);
    }
    
    // Jika tidak ada permission sama sekali, logout
    Auth::logout();
    return redirect()->route('login')->with('error', 'Akun Anda tidak memiliki akses ke sistem. Silakan hubungi administrator.');
    
})->middleware('auth')->name('root');

// Route home - sama seperti root
Route::get('/home', function () {
    return redirect('/');
})->middleware('auth')->name('home');
