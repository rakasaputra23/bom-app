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

    // Helper method untuk cek permission
    public function hasPermission($routeName)
    {
        return $this->permissions()->where('route_name', $routeName)->exists();
    }
}