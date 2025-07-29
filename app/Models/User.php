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

    // PERBAIKAN UTAMA: Method isSuperAdmin yang lebih ketat - hanya "superadmin"
    public function isSuperAdmin()
    {
        // Eager load group jika belum
        if (!$this->relationLoaded('group')) {
            $this->load('group');
        }
        
        if (!$this->group) {
            return false;
        }

        // PERBAIKAN KRITIS: Hanya grup "superadmin" yang dianggap superadmin
        // Admin, administrator, dll harus mengikuti permission system
        $superAdminNames = ['superadmin', 'super admin']; // Hanya 2 variasi ini
        $groupName = strtolower(trim($this->group->nama));
        
        $isSuperAdmin = in_array($groupName, $superAdminNames);
        
        // Log untuk debugging
        \Log::info('SuperAdmin Check', [
            'user_id' => $this->id,
            'group_name' => $groupName,
            'is_superadmin' => $isSuperAdmin,
            'allowed_names' => $superAdminNames
        ]);
        
        return $isSuperAdmin;
    }

    // NEW: Method untuk cek apakah user adalah admin biasa (bukan superadmin)
    public function isRegularAdmin()
    {
        if (!$this->relationLoaded('group')) {
            $this->load('group');
        }
        
        if (!$this->group) {
            return false;
        }

        // Admin biasa yang harus mengikuti permission system
        $adminNames = ['admin', 'administrator'];
        $groupName = strtolower(trim($this->group->nama));
        
        return in_array($groupName, $adminNames) && !$this->isSuperAdmin();
    }

    // PERBAIKAN: Method hasPermission yang dioptimasi dengan kontrol lebih ketat
    public function hasPermission($routeName)
    {
        if (!$routeName) {
            return false;
        }

        // HANYA superadmin yang bypass permission check
        if ($this->isSuperAdmin()) {
            \Log::info('Superadmin bypass permission check', [
                'user_id' => $this->id,
                'route_name' => $routeName,
                'group_name' => $this->group->nama ?? 'No Group'
            ]);
            return true;
        }

        // SEMUA USER LAINNYA (termasuk admin biasa) harus cek permission
        // Pastikan group dan permissions ter-load
        if (!$this->relationLoaded('group')) {
            $this->load('group.permissions');
        }

        if (!$this->group) {
            \Log::warning('User has no group', ['user_id' => $this->id]);
            return false;
        }

        // Cache key yang lebih spesifik
        $cacheKey = "user_{$this->id}_has_permission_{$routeName}";
        
        return Cache::remember($cacheKey, 300, function () use ($routeName) {
            if (!$this->group->permissions) {
                \Log::info('Group has no permissions', [
                    'user_id' => $this->id,
                    'group_id' => $this->group->id,
                    'group_name' => $this->group->nama
                ]);
                return false;
            }
            
            $userPermissions = $this->group->permissions->pluck('route_name')->toArray();
            $hasPermission = in_array($routeName, $userPermissions);
            
            \Log::info('Permission check result', [
                'user_id' => $this->id,
                'user_nip' => $this->nip,
                'group_name' => $this->group->nama,
                'route_name' => $routeName,
                'user_permissions' => $userPermissions,
                'has_permission' => $hasPermission,
                'is_regular_admin' => $this->isRegularAdmin()
            ]);
            
            return $hasPermission;
        });
    }

    // Method hasAnyPermission untuk mengecek multiple permissions
    public function hasAnyPermission($permissions)
    {
        // HANYA superadmin yang bypass
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Admin biasa dan user lain harus cek satu per satu
        foreach ((array) $permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    // PERBAIKAN: Method canAccessModule dengan kontrol ketat
    public function canAccessModule($module)
    {
        // HANYA superadmin yang bisa akses semua module tanpa cek
        if ($this->isSuperAdmin()) {
            \Log::info('Superadmin accessing module', [
                'user_id' => $this->id,
                'module' => $module
            ]);
            return true;
        }

        // SEMUA USER LAIN (termasuk admin) harus cek permission per module
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
        $canAccess = $this->hasAnyPermission($permissions);
        
        \Log::info('Module access check', [
            'user_id' => $this->id,
            'user_group' => $this->group->nama ?? 'No Group',
            'module' => $module,
            'required_permissions' => $permissions,
            'can_access' => $canAccess,
            'is_regular_admin' => $this->isRegularAdmin()
        ]);
        
        return $canAccess;
    }

    // NEW: Method untuk mendapatkan route pertama yang bisa diakses user
    public function getFirstAccessibleRoute()
    {
        // HANYA superadmin yang otomatis ke dashboard
        if ($this->isSuperAdmin()) {
            return 'dashboard';
        }

        // SEMUA USER LAIN (termasuk admin) harus cek permission
        // Load permissions jika belum
        if (!$this->relationLoaded('group')) {
            $this->load('group.permissions');
        }

        if (!$this->group || !$this->group->permissions) {
            \Log::warning('User has no accessible routes', [
                'user_id' => $this->id,
                'has_group' => !!$this->group,
                'has_permissions' => $this->group ? !!$this->group->permissions : false
            ]);
            return null;
        }

        $userPermissions = $this->group->permissions->pluck('route_name')->toArray();

        // Prioritas route yang akan di-redirect (urut berdasarkan kepentingan)
        $routePriorities = [
            'dashboard', // Jika punya akses dashboard
            'bom.index', // BOM sebagai prioritas kedua
            'kode-material.index', // Master data material
            'proyek.index', // Master data proyek
            'uom.index', // Master data UOM
            'revisi.index', // Master data revisi
            'user', // User management
        ];

        // Cari route pertama yang user punya akses
        foreach ($routePriorities as $route) {
            if (in_array($route, $userPermissions)) {
                \Log::info('First accessible route found', [
                    'user_id' => $this->id,
                    'route' => $route,
                    'is_regular_admin' => $this->isRegularAdmin()
                ]);
                return $route;
            }
        }

        // Jika tidak ada dari prioritas, ambil permission pertama yang ada
        $firstRoute = $userPermissions[0] ?? null;
        
        \Log::info('Using first available permission as route', [
            'user_id' => $this->id,
            'first_route' => $firstRoute,
            'all_permissions' => $userPermissions,
            'is_regular_admin' => $this->isRegularAdmin()
        ]);
        
        return $firstRoute;
    }

    // Method untuk mendapatkan semua permissions user
    public function getAllPermissions()
    {
        // HANYA superadmin yang dapat semua permissions
        if ($this->isSuperAdmin()) {
            return \App\Models\Permission::all();
        }

        // Admin biasa dan user lain hanya dapat permission yang diberikan
        // Load group dengan permissions jika belum
        if (!$this->relationLoaded('group')) {
            $this->load('group.permissions');
        }

        return $this->group ? $this->group->permissions : collect();
    }

    // NEW: Method untuk mendapatkan user role yang lebih deskriptif
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

    // NEW: Method untuk cek apakah user bisa manage users lain
    public function canManageUsers()
    {
        return $this->isSuperAdmin() || 
               $this->hasAnyPermission(['user', 'user.store', 'user.update', 'user.destroy']);
    }

    // NEW: Method untuk cek apakah user bisa manage user groups
    public function canManageUserGroups()
    {
        return $this->isSuperAdmin() || 
               $this->hasAnyPermission(['user.group', 'user.group.store', 'user.group.update', 'user.group.destroy']);
    }

    // NEW: Method untuk cek apakah user bisa manage permissions
    public function canManagePermissions()
    {
        return $this->isSuperAdmin() || 
               $this->hasAnyPermission(['permissions.index', 'permissions.store', 'permissions.update', 'permissions.destroy']);
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
        
        \Log::info('Permission cache cleared', [
            'user_id' => $this->id,
            'cache_keys_count' => count($cacheKeys)
        ]);
    }

    // Event hooks untuk clear cache saat user diupdate
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($user) {
            $user->clearPermissionCache();
            \Log::info('User updated, cache cleared', ['user_id' => $user->id]);
        });

        static::saved(function ($user) {
            $user->clearPermissionCache();
            \Log::info('User saved, cache cleared', ['user_id' => $user->id]);
        });
    }
}