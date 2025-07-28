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

        // PERBAIKAN: Log untuk debugging
        Log::info('Permission Check', [
            'user_id' => $user->id,
            'user_nip' => $user->nip,
            'route_name' => $routeName,
            'user_group' => $user->group ? $user->group->nama : 'No Group'
        ]);

        // PERBAIKAN: Load user group dan permissions
        $user->load('group.permissions');

        // Superadmin selalu lolos
        if ($user->isSuperAdmin()) {
            Log::info('Access granted: Superadmin');
            return $next($request);
        }

        // Pastikan user punya grup
        if (!$user->group) {
            Log::warning('Access denied: No user group', ['user_id' => $user->id]);
            abort(403, 'Akun Anda belum memiliki grup user.');
        }

        // PERBAIKAN: Routes yang tidak perlu dicek permission
        $publicRoutes = [
            'logout',
            'password.request',
            'password.reset',
            'password.update'
        ];

        if (in_array($routeName, $publicRoutes)) {
            return $next($request);
        }

        // Cek apakah grup punya izin akses route ini
        if (!$user->hasPermission($routeName)) {
            Log::warning('Access denied: No permission', [
                'user_id' => $user->id,
                'route_name' => $routeName,
                'user_permissions' => $user->group->permissions->pluck('route_name')->toArray()
            ]);
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        Log::info('Access granted: Permission found');
        return $next($request);
    }
}