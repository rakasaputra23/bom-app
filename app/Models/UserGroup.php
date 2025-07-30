<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class UserGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama'
    ];

    // Relasi ke User
    public function users()
    {
        return $this->hasMany(User::class, 'user_group_id');
    }

    // Relasi ke Permission melalui group_permissions
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'group_permissions', 'user_group_id', 'permission_id');
    }

    // Method untuk assign permission ke group
    public function givePermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('route_name', $permission)->first();
        }

        if ($permission && !$this->permissions->contains($permission->id)) {
            $this->permissions()->attach($permission->id);
        }

        return $this;
    }

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($userGroup) {
            // Force refresh semua user dalam group ini
            $users = $userGroup->users;
            foreach ($users as $user) {
                $user->refreshRelations();
            }
            
            Log::info('UserGroup updated, user relations refreshed', [
                'group_id' => $userGroup->id,
                'users_count' => $users->count()
            ]);
        });
    }

    // Method untuk revoke permission dari group
    public function revokePermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('route_name', $permission)->first();
        }

        if ($permission) {
            $this->permissions()->detach($permission->id);
        }

        return $this;
    }
}