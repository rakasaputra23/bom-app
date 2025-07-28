<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserGroup extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];

    public function users()
    {
        return $this->hasMany(User::class, 'user_group_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'group_permissions', 'user_group_id', 'permission_id');
    }

    // PERBAIKAN: Method hasPermission yang lebih efisien
    public function hasPermission($routeName)
    {
        if (!$routeName) {
            return false;
        }

        // Gunakan whereHas untuk query yang lebih efisien
        return $this->permissions()->where('route_name', $routeName)->exists();
    }

    // TAMBAHAN: Method untuk sync permissions
    public function syncPermissions(array $permissionIds)
    {
        return $this->permissions()->sync($permissionIds);
    }
}
