<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_name',
        'deskripsi'
    ];

    // Relasi ke UserGroup melalui group_permissions
    public function userGroups()
    {
        return $this->belongsToMany(UserGroup::class, 'group_permissions', 'permission_id', 'user_group_id');
    }
}