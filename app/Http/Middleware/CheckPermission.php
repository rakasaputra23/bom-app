<?php

// ========================================
// 1. IMPROVED MIDDLEWARE (CheckPermission.php)
// ========================================

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

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

        // Log untuk debugging (hanya di development)
        if (config('app.debug')) {
            Log::info('Permission Check', [
                'user_id' => $user->id,
                'user_nip' => $user->nip,
                'route_name' => $routeName,
                'user_group' => $user->group ? $user->group->nama : 'No Group',
                'is_superadmin' => $user->isSuperAdmin()
            ]);
        }

        // Load user group dan permissions - FRESH LOAD untuk menghindari cache issue
        $user->load('group.permissions');

        // Superadmin selalu lolos - HANYA untuk grup "superadmin"
        if ($user->isSuperAdmin()) {
            Log::info('User is superadmin, allowing access', ['user_id' => $user->id]);
            return $next($request);
        }

        // Pastikan user punya grup
        if (!$user->group) {
            Log::warning('Access denied: No user group', ['user_id' => $user->id]);
            abort(403, 'Akun Anda belum memiliki grup user. Silakan hubungi administrator.');
        }

        // Routes yang tidak perlu dicek permission (public routes)
        $publicRoutes = [
            'logout',
            'password.request',
            'password.reset',
            'password.update',
            'profile',
        ];

        if (in_array($routeName, $publicRoutes)) {
            return $next($request);
        }

        // PERBAIKAN UTAMA: Hilangkan cache untuk memastikan permission selalu fresh
        // Atau gunakan cache dengan TTL yang sangat pendek (30 detik)
        $cacheKey = "user_permissions_{$user->id}_route_{$routeName}";
        $hasPermission = Cache::remember($cacheKey, 30, function () use ($user, $routeName) {
            return $user->hasPermission($routeName);
        });

        if (!$hasPermission) {
            Log::warning('Access denied: No permission', [
                'user_id' => $user->id,
                'route_name' => $routeName,
                'user_group' => $user->group->nama,
                'is_regular_admin' => $user->isRegularAdmin(),
                'user_permissions' => $user->group->permissions->pluck('route_name')->toArray()
            ]);
            
            // PERBAIKAN: Redirect ke halaman yang user punya akses
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Anda tidak memiliki izin untuk mengakses resource ini.'
                ], 403);
            }
            
            // Jika tidak punya akses dashboard, redirect ke halaman pertama yang bisa diakses
            if ($routeName === 'dashboard' || $routeName === 'home' || $routeName === 'root') {
                $firstAccessibleRoute = $user->getFirstAccessibleRoute();
                if ($firstAccessibleRoute) {
                    return redirect()->route($firstAccessibleRoute);
                }
            }
            
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}