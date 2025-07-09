<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KodeMaterialController;
use App\Http\Controllers\UomController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Password Reset Routes (Static Views Only)
Route::get('/password/reset', function () {
    return view('auth.passwords.email');
})->name('password.request');

// Halaman update password statis
Route::get('/password/update', function () {
    return view('auth.passwords.reset', [
        'token' => 'static-token-example',
        'email' => 'example@email.com' // Optional: jika ingin menampilkan email contoh
    ]);
})->name('password.update');

// Dashboard & Profile
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

// Tambahkan route untuk edit profile
Route::get('/profile/edit', function () {
    return view('profile.edit');
})->name('profile.edit');

Route::prefix('master')->group(function () {
    Route::prefix('kode-material')->group(function () {
        Route::get('/', [KodeMaterialController::class, 'index'])->name('kode-material.index');
        Route::get('/data', [KodeMaterialController::class, 'getData'])->name('kode-material.getData');
        Route::post('/', [KodeMaterialController::class, 'store'])->name('kode-material.store');
        Route::get('/{kodeMaterial}', [KodeMaterialController::class, 'show'])->name('kode-material.show');
        Route::put('/{kodeMaterial}', [KodeMaterialController::class, 'update'])->name('kode-material.update');
        Route::delete('/{kodeMaterial}', [KodeMaterialController::class, 'destroy'])->name('kode-material.destroy');
    });

Route::prefix('master/uom')->group(function () {
    Route::get('/', [UomController::class, 'index'])->name('uom.index');
    Route::get('/data', [UomController::class, 'getData'])->name('uom.getData');
    Route::post('/', [UomController::class, 'store'])->name('uom.store');
    Route::get('/{uom}', [UomController::class, 'show'])->name('uom.show');
    Route::put('/{uom}', [UomController::class, 'update'])->name('uom.update');
    Route::delete('/{uom}', [UomController::class, 'destroy'])->name('uom.destroy');
});


    // Other master routes
    Route::view('/revisi', 'master.revisi')->name('revisi');
    Route::view('/proyek', 'master.proyek')->name('proyek');
});

// User Routes
Route::view('/user', 'user.user')->name('user');
Route::view('/user-group', 'user.user-group')->name('user.group');


// BOM Routes
Route::prefix('bom')->group(function () {
    Route::get('/', function () {
        return view('bom.index');
    })->name('bom.index');
    
    Route::get('/create', function () {
        return view('bom.create');
    })->name('bom.create');
});
