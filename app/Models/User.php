<?php

// ========================================
// 1. PERBAIKAN MODEL USER
// ========================================

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Login menggunakan NIP
    public function getAuthIdentifierName()
    {
        return 'nip';
    }

    // Relasi ke UserGroup (PERBAIKAN: eager loading)
    public function group()
    {
        return $this->belongsTo(UserGroup::class, 'user_group_id');
    }

    // PERBAIKAN: Method hasPermission yang lebih robust
    public function hasPermission($routeName)
    {
        // Jika tidak ada route name, return false
        if (!$routeName) {
            return false;
        }

        // Superadmin selalu punya akses
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Load group dengan permissions jika belum di-load
        if (!$this->relationLoaded('group')) {
            $this->load('group.permissions');
        }

        // Cek apakah user punya group dan group punya permission
        return $this->group && $this->group->hasPermission($routeName);
    }

    // PERBAIKAN: Method isSuperAdmin yang lebih aman
    public function isSuperAdmin()
    {
        if (!$this->group) {
            // Load group jika belum ter-load
            $this->load('group');
        }
        
        return $this->group && in_array(strtolower($this->group->nama), ['superadmin', 'super admin']);
    }

    // TAMBAHAN: Method untuk mendapatkan semua permissions user
    public function getAllPermissions()
    {
        if ($this->isSuperAdmin()) {
            return Permission::all();
        }

        return $this->group ? $this->group->permissions : collect();
    }
}