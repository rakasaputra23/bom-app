<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nip',
        'nama',
        'posisi',
        'user_group_id',
        'email',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Login menggunakan NIP
    public function getAuthIdentifierName()
    {
        return 'nip';
    }

    // Relasi ke UserGroup
    public function group()
    {
        return $this->belongsTo(UserGroup::class, 'user_group_id');
    }

    // Method untuk mendapatkan nama lengkap
    public function getFullNameAttribute()
    {
        return $this->nama;
    }

    // PERBAIKAN UTAMA: Method isSuperAdmin yang lebih ketat
    public function isSuperAdmin()
    {
        // SELALU load fresh untuk avoid cache issue
        $this->loadMissing('group');
        
        if (!$this->group) {
            return false;
        }

        // HANYA grup "superadmin" yang dianggap superadmin
        $superAdminNames = ['superadmin', 'super admin'];
        $groupName = strtolower(trim($this->group->nama));
        
        return in_array($groupName, $superAdminNames);
    }

    // Method untuk cek apakah user adalah admin biasa (bukan superadmin)
    public function isRegularAdmin()
    {
        $this->loadMissing('group');
        
        if (!$this->group) {
            return false;
        }

        $adminNames = ['admin', 'administrator'];
        $groupName = strtolower(trim($this->group->nama));
        
        return in_array($groupName, $adminNames) && !$this->isSuperAdmin();
    }

    // PERBAIKAN KRITIS: Method hasPermission tanpa cache yang bermasalah
    public function hasPermission($routeName)
    {
        if (!$routeName) {
            return false;
        }

        // HANYA superadmin yang bypass permission check
        if ($this->isSuperAdmin()) {
            return true;
        }

        // LOAD FRESH permissions setiap kali untuk menghindari cache issue
        $this->loadMissing('group.permissions');

        if (!$this->group || !$this->group->permissions) {
            return false;
        }

        // CHECK langsung tanpa cache
        $userPermissions = $this->group->permissions->pluck('route_name')->toArray();
        $hasPermission = in_array($routeName, $userPermissions);
        
        // Log hanya untuk debugging jika diperlukan
        if (config('app.debug')) {
            Log::info('Permission check', [
                'user_id' => $this->id,
                'route_name' => $routeName,
                'has_permission' => $hasPermission,
                'user_permissions' => $userPermissions
            ]);
        }
        
        return $hasPermission;
    }

    // Method hasAnyPermission untuk mengecek multiple permissions
    public function hasAnyPermission($permissions)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        foreach ((array) $permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    // PERBAIKAN: Method canAccessModule
    public function canAccessModule($module)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $modulePermissions = [
            'master_data' => [
                'kode-material.index', 
                'kode-material.getData',
                'kode-material.show',
                'kode-material.store',
                'kode-material.update',
                'kode-material.destroy',
                'revisi.index', 
                'revisi.getData',
                'revisi.store',
                'revisi.update',
                'revisi.destroy',
                'proyek.index', 
                'proyek.getData',
                'proyek.show',
                'proyek.store',
                'proyek.update',
                'proyek.destroy',
                'uom.index',
                'uom.getData',
                'uom.show',
                'uom.store',
                'uom.update',
                'uom.destroy'
            ],
            'bom' => [
                'bom.index', 
                'bom.create', 
                'bom.show', 
                'bom.edit',
                'bom.store',
                'bom.update',
                'bom.destroy'
            ],
            'user_management' => [
                'user', 
                'user.getData',
                'user.show',
                'user.store',
                'user.update',
                'user.destroy',
                'user.group',
                'user.group.getData',
                'user.group.show',
                'user.group.store',
                'user.group.update',
                'user.group.destroy',
                'user.group.permissions',
                'permissions.index',
                'permissions.getData',
                'permissions.show',
                'permissions.store',
                'permissions.update',
                'permissions.destroy'
            ],
            'dashboard' => [
                'dashboard'
            ]
        ];

        $permissions = $modulePermissions[$module] ?? [];
        return $this->hasAnyPermission($permissions);
    }

    // PERBAIKAN: Method getFirstAccessibleRoute
    public function getFirstAccessibleRoute()
    {
        if ($this->isSuperAdmin()) {
            return 'dashboard';
        }

        // Load fresh permissions
        $this->loadMissing('group.permissions');

        if (!$this->group || !$this->group->permissions) {
            return null;
        }

        $userPermissions = $this->group->permissions->pluck('route_name')->toArray();

        // Prioritas route
        $routePriorities = [
            'dashboard',
            'bom.index',
            'kode-material.index',
            'proyek.index',
            'uom.index',
            'revisi.index',
            'user',
        ];

        foreach ($routePriorities as $route) {
            if (in_array($route, $userPermissions)) {
                return $route;
            }
        }

        return $userPermissions[0] ?? null;
    }

    // Method untuk mendapatkan semua permissions user
    public function getAllPermissions()
    {
        if ($this->isSuperAdmin()) {
            return \App\Models\Permission::all();
        }

        $this->loadMissing('group.permissions');
        return $this->group ? $this->group->permissions : collect();
    }

    // Method untuk mendapatkan user role yang lebih deskriptif
    public function getUserRole()
    {
        if ($this->isSuperAdmin()) {
            return 'superadmin';
        }
        
        if ($this->isRegularAdmin()) {
            return 'admin';
        }
        
        return $this->group ? strtolower($this->group->nama) : 'user';
    }

    // Method untuk cek apakah user bisa manage users lain
    public function canManageUsers()
    {
        return $this->isSuperAdmin() || 
               $this->hasAnyPermission(['user', 'user.store', 'user.update', 'user.destroy']);
    }

    // Method untuk cek apakah user bisa manage user groups
    public function canManageUserGroups()
    {
        return $this->isSuperAdmin() || 
               $this->hasAnyPermission(['user.group', 'user.group.store', 'user.group.update', 'user.group.destroy']);
    }

    // Method untuk cek apakah user bisa manage permissions
    public function canManagePermissions()
    {
        return $this->isSuperAdmin() || 
               $this->hasAnyPermission(['permissions.index', 'permissions.store', 'permissions.update', 'permissions.destroy']);
    }

    // PERBAIKAN: Method untuk refresh user relationship - PENTING!
    public function refreshRelations()
    {
        // Unload existing relationships
        unset($this->relations['group']);
        
        // Load fresh
        $this->load('group.permissions');
        
        return $this;
    }

    // BARU: Static method untuk refresh specific user dari database
    public static function findAndRefresh($id)
    {
        $user = static::with('group.permissions')->find($id);
        return $user;
    }

    // Event hooks - DIPERBAIKI untuk force refresh
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($user) {
            // Force refresh relationships
            $user->refreshRelations();
            
            Log::info('User updated, relationships refreshed', ['user_id' => $user->id]);
        });

        static::saved(function ($user) {
            // Force refresh relationships  
            $user->refreshRelations();
            
            Log::info('User saved, relationships refreshed', ['user_id' => $user->id]);
        });
    }
}