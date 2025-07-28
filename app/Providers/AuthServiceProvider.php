<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Permission;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Auto-define Gates for all permissions
        try {
            Permission::all()->each(function ($permission) {
                Gate::define($permission->route_name, function ($user) use ($permission) {
                    return $user->hasPermission($permission->route_name);
                });
            });
        } catch (\Exception $e) {
            // Avoid error during migration/seed
            \Log::warning('Skipping Gate definitions: ' . $e->getMessage());
        }
    }
}
