<?php

use Illuminate\Support\Facades\Route;

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

// Master Data Routes
Route::view('/kode-material', 'master.kode_material')->name('kode.material');
Route::view('/revisi', 'master.revisi')->name('revisi');
Route::view('/proyek', 'master.proyek')->name('proyek');
Route::view('/uom', 'master.uom')->name('uom');

// BOM Routes
Route::prefix('bom')->group(function () {
    Route::get('/', function () {
        return view('bom.index');
    })->name('bom.index');
    
    Route::get('/create', function () {
        return view('bom.create');
    })->name('bom.create');
});