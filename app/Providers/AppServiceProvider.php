<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // PERBAIKAN: Global helper functions dengan logging
        if (!function_exists('canView')) {
            function canView($permission) {
                if (!Auth::check()) {
                    return false;
                }
                
                $result = Auth::user()->hasPermission($permission);
                
                // Debug logging
                if (config('app.debug')) {
                    Log::info('canView helper called', [
                        'permission' => $permission,
                        'user_id' => Auth::id(),
                        'result' => $result,
                        'is_superadmin' => Auth::user()->isSuperAdmin()
                    ]);
                }
                
                return $result;
            }
        }

        if (!function_exists('canAccessModule')) {
            function canAccessModule($module) {
                if (!Auth::check()) {
                    return false;
                }
                
                $result = Auth::user()->canAccessModule($module);
                
                // Debug logging
                if (config('app.debug')) {
                    Log::info('canAccessModule helper called', [
                        'module' => $module,
                        'user_id' => Auth::id(),
                        'result' => $result,
                        'is_superadmin' => Auth::user()->isSuperAdmin()
                    ]);
                }
                
                return $result;
            }
        }

        if (!function_exists('isSuperAdmin')) {
            function isSuperAdmin() {
                if (!Auth::check()) {
                    return false;
                }
                
                $result = Auth::user()->isSuperAdmin();
                
                // Debug logging
                if (config('app.debug')) {
                    Log::info('isSuperAdmin helper called', [
                        'user_id' => Auth::id(),
                        'result' => $result,
                        'user_group' => Auth::user()->group ? Auth::user()->group->nama : 'No Group'
                    ]);
                }
                
                return $result;
            }
        }

        // Share user data to all views dengan eager loading
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                
                // Pastikan group ter-load
                if (!$user->relationLoaded('group')) {
                    $user->load('group.permissions');
                }
                
                $view->with([
                    'currentUser' => $user,
                    'userGroup' => $user->group,
                ]);
            }
        });
    }
}