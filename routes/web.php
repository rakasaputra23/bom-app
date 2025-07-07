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

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/password/reset', function () {
    return view('auth.passwords.email');
})->name('password.request');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::view('/kode-material', 'master.kode_material')->name('kode.material');
Route::view('/revisi', 'master.revisi')->name('revisi');
Route::view('/proyek', 'master.proyek')->name('proyek');
Route::view('/yuom', 'master.yuom')->name('yuom');
