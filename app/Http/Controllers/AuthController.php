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

        if (Auth::attempt(['nip' => $request->nip, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        } else {
            // Password salah
            return back()->with('error', 'Password yang Anda masukkan salah')->onlyInput('nip');
        }

        return back()->withErrors([
            'nip' => 'NIP atau password salah.',
        ])->onlyInput('nip');
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