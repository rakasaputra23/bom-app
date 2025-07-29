<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

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

    // PERBAIKAN: Method isSuperAdmin yang lebih robust
    public function isSuperAdmin()
    {
        // Eager load group jika belum
        if (!$this->relationLoaded('group')) {
            $this->load('group');
        }
        
        if (!$this->group) {
            return false;
        }

        // PERBAIKAN: Cek nama grup dengan lebih teliti
        $superAdminNames = ['superadmin', 'super admin', 'administrator', 'admin'];
        $groupName = strtolower(trim($this->group->nama));
        
        return in_array($groupName, $superAdminNames);
    }

    // PERBAIKAN: Method hasPermission yang dioptimasi
    public function hasPermission($routeName)
    {
        if (!$routeName) {
            return false;
        }

        // Superadmin selalu punya akses - CRITICAL FIX
        if ($this->isSuperAdmin()) {
            \Log::info('Superadmin check passed for route: ' . $routeName, ['user_id' => $this->id]);
            return true;
        }

        // Pastikan group dan permissions ter-load
        if (!$this->relationLoaded('group')) {
            $this->load('group.permissions');
        }

        if (!$this->group) {
            return false;
        }

        // Cache key yang lebih spesifik
        $cacheKey = "user_{$this->id}_has_permission_{$routeName}";
        
        return Cache::remember($cacheKey, 300, function () use ($routeName) {
            if (!$this->group->permissions) {
                return false;
            }
            
            $userPermissions = $this->group->permissions->pluck('route_name')->toArray();
            \Log::info('Checking permission', [
                'user_id' => $this->id,
                'route_name' => $routeName,
                'user_permissions' => $userPermissions,
                'has_permission' => in_array($routeName, $userPermissions)
            ]);
            
            return in_array($routeName, $userPermissions);
        });
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

    // PERBAIKAN: Method canAccessModule yang lebih comprehensive
    public function canAccessModule($module)
    {
        // Superadmin bisa akses semua module
        if ($this->isSuperAdmin()) {
            return true;
        }

        // PERBAIKAN: Mapping module permissions yang lebih lengkap
        $modulePermissions = [
            'master_data' => [
                'kode-material.index', 
                'kode-material.getData',
                'kode-material.show',
                'revisi.index', 
                'revisi.getData',
                'proyek.index', 
                'proyek.getData',
                'proyek.show',
                'uom.index',
                'uom.getData',
                'uom.show'
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
                'user.group',
                'user.group.getData',
                'user.group.show',
                'user.group.permissions',
                'permissions.index',
                'permissions.getData',
                'permissions.show'
            ]
        ];

        $permissions = $modulePermissions[$module] ?? [];
        return $this->hasAnyPermission($permissions);
    }

    // Method untuk mendapatkan semua permissions user
    public function getAllPermissions()
    {
        // Untuk superadmin, return semua permissions
        if ($this->isSuperAdmin()) {
            return \App\Models\Permission::all();
        }

        // Load group dengan permissions jika belum
        if (!$this->relationLoaded('group')) {
            $this->load('group.permissions');
        }

        return $this->group ? $this->group->permissions : collect();
    }

    // Method untuk clear permission cache
    public function clearPermissionCache()
    {
        $cacheKeys = [
            "user_{$this->id}_permissions",
            "user_permissions_{$this->id}"
        ];

        // Clear semua cache yang berkaitan dengan user ini
        $permissions = \App\Models\Permission::pluck('route_name');
        foreach ($permissions as $permission) {
            $cacheKeys[] = "user_{$this->id}_has_permission_{$permission}";
            $cacheKeys[] = "user_permissions_{$this->id}_route_{$permission}";
        }

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }

    // Event hooks untuk clear cache saat user diupdate
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($user) {
            $user->clearPermissionCache();
        });

        static::saved(function ($user) {
            $user->clearPermissionCache();
        });
    }
}