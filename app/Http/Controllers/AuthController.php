<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'password' => 'required',
        ]);

        // Cek apakah NIP terdaftar
        $user = User::where('nip', $request->nip)->first();

        if (!$user) {
            return back()->withErrors(['nip' => 'NIP tidak terdaftar.'])
                         ->onlyInput('nip');
        }

        // Cek password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah.'])
                         ->onlyInput('nip');
        }

        // Jika semua valid, lakukan login
        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showForgotPassword()
    {
        return view('auth.passwords.email');
    }

    public function showResetPasswordForm()
    {
        return view('auth.passwords.reset', [
            'token' => 'static-token-example',
            'email' => 'example@email.com',
        ]);
    }

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