<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $routeName = $request->route()->getName();

        // Superadmin selalu lolos
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Pastikan user punya grup
        if (!$user->group) {
            abort(403, 'Akun Anda belum memiliki grup user.');
        }

        // Cek apakah grup punya izin akses route ini
        if (!$user->hasPermission($routeName)) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
