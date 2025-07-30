<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        // PENTING: Selalu refresh relations untuk memastikan data terbaru
        $user->refreshRelations();

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
        ];

        if (in_array($routeName, $publicRoutes)) {
            return $next($request);
        }

        // PERBAIKAN: Langsung check permission tanpa cache
        if (!$user->hasPermission($routeName)) {
            Log::warning('Access denied: No permission', [
                'user_id' => $user->id,
                'route_name' => $routeName,
                'user_group' => $user->group->nama,
                'is_regular_admin' => $user->isRegularAdmin(),
                'user_permissions' => $user->group->permissions ? $user->group->permissions->pluck('route_name')->toArray() : []
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Anda tidak memiliki izin untuk mengakses resource ini.'
                ], 403);
            }
            
            // Jika tidak punya akses dashboard, redirect ke halaman pertama yang bisa diakses
            if (in_array($routeName, ['dashboard', 'home', 'root'])) {
                $firstAccessibleRoute = $user->getFirstAccessibleRoute();
                if ($firstAccessibleRoute && $firstAccessibleRoute !== $routeName) {
                    return redirect()->route($firstAccessibleRoute);
                }
            }
            
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}