<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'password' => 'required',
        ]);

        // Cek apakah NIP ada di database
        $user = User::where('nip', $request->nip)->first();
        
        if (!$user) {
            // NIP tidak ditemukan
            return back()->with('error', 'NIP tidak ditemukan dalam sistem')->onlyInput('nip');
        }
        
        // NIP ada, cek password
        if (Auth::attempt(['nip' => $request->nip, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        } else {
            // Password salah
            return back()->with('error', 'Password yang Anda masukkan salah')->onlyInput('nip');
        }
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // Halaman form lupa password
    public function showForgotPassword()
    {
        return view('auth.passwords.email');
    }

    // Halaman reset password manual (token dan email statis)
    public function showResetPasswordForm()
    {
        return view('auth.passwords.reset', [
            'token' => 'static-token-example',
            'email' => 'example@email.com',
        ]);
    }

    // Proses reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'nip' => 'required|exists:users,nip',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $user = User::where('nip', $request->nip)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('status', 'Password berhasil direset.');
    }
}