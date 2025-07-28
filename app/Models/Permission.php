<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_name',
        'deskripsi'
    ];

    public function userGroups()
    {
        return $this->belongsToMany(UserGroup::class, 'group_permissions', 'permission_id', 'user_group_id');
    }

    // TAMBAHAN: Method untuk mendapatkan permission berdasarkan route
    public static function getByRoute($routeName)
    {
        return static::where('route_name', $routeName)->first();
    }
}