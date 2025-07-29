<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // Custom Blade directive untuk check permission
        Blade::if('canView', function ($permission) {
            return Auth::check() && Auth::user()->hasPermission($permission);
        });

        Blade::if('canAccessModule', function ($module) {
            return Auth::check() && Auth::user()->canAccessModule($module);
        });

        Blade::if('superAdmin', function () {
            return Auth::check() && Auth::user()->isSuperAdmin();
        });

        // Custom directive untuk check multiple permissions
        Blade::if('hasAnyPermission', function (...$permissions) {
            return Auth::check() && Auth::user()->hasAnyPermission($permissions);
        });
    }
}