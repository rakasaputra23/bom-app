<?php

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

    // Relasi ke UserGroup
    public function group()
{
    return $this->belongsTo(UserGroup::class, 'user_group_id');
}

public function hasPermission($routeName)
{
    return $this->group && $this->group->hasPermission($routeName);
}


    // Helper method untuk cek apakah user adalah superadmin
    public function isSuperAdmin()
    {
        return $this->group && strtolower($this->group->nama) === 'superadmin';
    }
}