<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\PasswordReset;
use App\Mail\ResetPasswordMail;

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
            
            // Cleanup expired tokens setelah login
            PasswordReset::cleanupExpiredTokens();
            
            Log::info('User logged in', ['nip' => $request->nip]);
            
            return redirect()->intended('/dashboard');
        } else {
            // Password salah
            return back()->with('error', 'Password yang Anda masukkan salah')->onlyInput('nip');
        }
    }

    // Logout
    public function logout(Request $request)
    {
        $nip = Auth::user()->nip ?? 'Unknown';
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        Log::info('User logged out', ['nip' => $nip]);
        
        return redirect('/login');
    }

    // Halaman form lupa password (input NIP untuk kirim email)
    public function showForgotPassword()
    {
        return view('auth.passwords.email');
    }

    /**
     * UPDATED: Kirim email reset password dengan rate limiting yang lebih fleksibel
     */
    public function sendResetPasswordEmail(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nip' => 'required|string|min:5',
        ], [
            'nip.required' => 'NIP harus diisi.',
            'nip.min' => 'NIP minimal 5 karakter.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $nip = trim($request->nip);

        // Rate limiting yang lebih fleksibel: 1 menit cooldown setelah 3 attempt
        $ipKey = 'reset-password-ip-' . $request->ip();
        $nipKey = 'reset-password-nip-' . $nip;
        
        // Cek jika IP sedang dalam cooldown (1 menit)
        if (RateLimiter::tooManyAttempts($ipKey, 3)) {
            $seconds = RateLimiter::availableIn($ipKey);
            return back()->withErrors([
                'rate_limit' => 'Terlalu banyak permintaan dari IP ini. Silakan tunggu ' . $seconds . ' detik lagi.'
            ])->withInput();
        }

        // Cek jika NIP sedang dalam cooldown (1 menit)
        if (RateLimiter::tooManyAttempts($nipKey, 2)) {
            $seconds = RateLimiter::availableIn($nipKey);
            return back()->withErrors([
                'nip_rate_limit' => 'Terlalu banyak permintaan untuk NIP ini. Silakan tunggu ' . $seconds . ' detik lagi.'
            ])->withInput();
        }

        try {
            // Cari user berdasarkan NIP
            $user = User::where('nip', $nip)->first();
            
            if (!$user) {
                // Hit rate limiter untuk IP yang coba NIP tidak valid
                RateLimiter::hit($ipKey, 60); // 1 menit cooldown
                return back()->withErrors([
                    'nip' => 'NIP tidak ditemukan dalam sistem.'
                ])->withInput();
            }

            // Cek apakah user memiliki email
            if (empty($user->email)) {
                return back()->withErrors([
                    'email' => 'User dengan NIP tersebut tidak memiliki email. Hubungi administrator.'
                ])->withInput();
            }

            // Generate token
            $token = PasswordReset::generateToken($nip);
            
            // Kirim email
            Mail::to($user->email)->send(new ResetPasswordMail($user, $token));
            
            // Hit rate limiter hanya setelah berhasil kirim email
            RateLimiter::hit($ipKey, 60); // 1 menit cooldown untuk IP
            RateLimiter::hit($nipKey, 60); // 1 menit cooldown untuk NIP
            
            Log::info('Password reset email sent', [
                'nip' => $nip,
                'email' => $user->email,
                'ip' => $request->ip()
            ]);

            return back()->with('status', 
                'Link reset password telah dikirim ke email: ' . $user->email . '. Silakan periksa inbox atau folder spam.');

        } catch (\Exception $e) {
            Log::error('Failed to send reset password email', [
                'nip' => $nip,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip()
            ]);

            return back()->withErrors([
                'email_error' => 'Gagal mengirim email reset password: ' . $e->getMessage() . '. Silakan coba lagi atau hubungi administrator.'
            ])->withInput();
        }
    }

    // Halaman reset password form dengan token dari email
    public function showResetPasswordForm(Request $request)
    {
        $token = $request->get('token');
        $nip = $request->get('nip');

        // Jika tidak ada parameter dari email, tampilkan form manual lama
        if (!$token || !$nip) {
            return view('auth.passwords.reset', [
                'token' => 'static-token-example',
                'nip' => '',
                'is_manual' => true
            ]);
        }

        // Validasi token dari email
        if (!PasswordReset::validateToken($nip, $token)) {
            return redirect()->route('password.request')->withErrors([
                'expired_token' => 'Link reset password sudah tidak berlaku atau salah. Silakan minta reset password baru.'
            ]);
        }

        $user = User::where('nip', $nip)->first();
        if (!$user) {
            return redirect()->route('password.request')->withErrors([
                'user_not_found' => 'User tidak ditemukan.'
            ]);
        }

        return view('auth.passwords.reset', [
            'token' => $token,
            'nip' => $nip,
            'user' => $user,
            'is_manual' => false
        ]);
    }

    // Proses reset password (mendukung manual dan via email)
    public function resetPassword(Request $request)
    {
        // Jika ada token, berarti dari email
        if ($request->has('token') && $request->token !== 'static-token-example') {
            return $this->resetPasswordViaEmail($request);
        }
        
        // Jika tidak, proses manual seperti sebelumnya
        return $this->resetPasswordManual($request);
    }

    /**
     * Reset password via email dengan token
     */
    private function resetPasswordViaEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'nip' => 'required|exists:users,nip',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.required' => 'Password baru harus diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'nip.exists' => 'NIP tidak valid.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $nip = $request->nip;
        $token = $request->token;

        // Validasi token sekali lagi
        if (!PasswordReset::validateToken($nip, $token)) {
            return redirect()->route('password.request')->withErrors([
                'expired_token' => 'Token reset password sudah tidak berlaku. Silakan minta reset password baru.'
            ]);
        }

        try {
            // Update password user
            $user = User::where('nip', $nip)->first();
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            // Hapus token setelah berhasil digunakan
            PasswordReset::deleteToken($nip);

            Log::info('Password successfully reset via email', [
                'nip' => $nip,
                'ip' => $request->ip()
            ]);

            return redirect()->route('login')->with('status', 
                'Password berhasil direset. Silakan login dengan password baru Anda.');

        } catch (\Exception $e) {
            Log::error('Failed to reset password via email', [
                'nip' => $nip,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip()
            ]);

            return back()->withErrors([
                'reset_error' => 'Gagal mereset password: ' . $e->getMessage() . '. Silakan coba lagi atau hubungi administrator.'
            ])->withInput();
        }
    }

    /**
     * Reset password manual (metode lama)
     */
    private function resetPasswordManual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|exists:users,nip',
            'password' => 'required|string|confirmed|min:6',
        ], [
            'nip.required' => 'NIP harus diisi.',
            'nip.exists' => 'NIP tidak ditemukan dalam sistem.',
            'password.required' => 'Password baru harus diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = User::where('nip', $request->nip)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            Log::info('Manual password reset successful', [
                'nip' => $request->nip,
                'reset_by_ip' => $request->ip()
            ]);

            return redirect()->route('login')->with('status', 'Password berhasil direset.');
        } catch (\Exception $e) {
            Log::error('Manual password reset failed', [
                'nip' => $request->nip,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'reset_error' => 'Gagal mereset password: ' . $e->getMessage() . '. Silakan coba lagi.'
            ])->withInput();
        }
    }
}