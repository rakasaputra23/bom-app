<?php

// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
{
    try {
        return view('dashboard');
    } catch (\Exception $e) {
        abort(403, 'Akses ditolak atau terjadi kesalahan sistem');
    }
}
}