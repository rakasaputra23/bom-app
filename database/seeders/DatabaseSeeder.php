<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create User Groups
        $superadminGroup = UserGroup::create(['nama' => 'Superadmin']);
        $produksiGroup = UserGroup::create(['nama' => 'Produksi']);
        $userGroup = UserGroup::create(['nama' => 'User']);

        // Create Permissions
        $permissions = [
            // User Management
            ['route_name' => 'user', 'deskripsi' => 'Lihat Daftar User'],
            ['route_name' => 'user.store', 'deskripsi' => 'Tambah User Baru'],
            ['route_name' => 'user.update', 'deskripsi' => 'Edit User'],
            ['route_name' => 'user.destroy', 'deskripsi' => 'Hapus User'],
            
            // User Group Management
            ['route_name' => 'user.group', 'deskripsi' => 'Lihat Daftar User Group'],
            ['route_name' => 'user.group.store', 'deskripsi' => 'Tambah User Group'],
            ['route_name' => 'user.group.update', 'deskripsi' => 'Edit User Group'],
            ['route_name' => 'user.group.destroy', 'deskripsi' => 'Hapus User Group'],
            
            // Dashboard & Profile
            ['route_name' => 'dashboard', 'deskripsi' => 'Akses Dashboard'],
            ['route_name' => 'profile', 'deskripsi' => 'Lihat Profile'],
            ['route_name' => 'profile.edit', 'deskripsi' => 'Edit Profile'],
            
            // Master Data
            ['route_name' => 'kode-material.index', 'deskripsi' => 'Lihat Kode Material'],
            ['route_name' => 'kode-material.store', 'deskripsi' => 'Tambah Kode Material'],
            ['route_name' => 'kode-material.update', 'deskripsi' => 'Edit Kode Material'],
            ['route_name' => 'kode-material.destroy', 'deskripsi' => 'Hapus Kode Material'],
            
            ['route_name' => 'uom.index', 'deskripsi' => 'Lihat UOM'],
            ['route_name' => 'uom.store', 'deskripsi' => 'Tambah UOM'],
            ['route_name' => 'uom.update', 'deskripsi' => 'Edit UOM'],
            ['route_name' => 'uom.destroy', 'deskripsi' => 'Hapus UOM'],
            
            ['route_name' => 'proyek.index', 'deskripsi' => 'Lihat Proyek'],
            ['route_name' => 'proyek.store', 'deskripsi' => 'Tambah Proyek'],
            ['route_name' => 'proyek.update', 'deskripsi' => 'Edit Proyek'],
            ['route_name' => 'proyek.destroy', 'deskripsi' => 'Hapus Proyek'],
            
            ['route_name' => 'revisi.index', 'deskripsi' => 'Lihat Revisi'],
            ['route_name' => 'revisi.store', 'deskripsi' => 'Tambah Revisi'],
            ['route_name' => 'revisi.update', 'deskripsi' => 'Edit Revisi'],
            ['route_name' => 'revisi.destroy', 'deskripsi' => 'Hapus Revisi'],
            
            // BOM
            ['route_name' => 'bom.index', 'deskripsi' => 'Lihat BOM'],
            ['route_name' => 'bom.create', 'deskripsi' => 'Buat BOM'],
            ['route_name' => 'bom.store', 'deskripsi' => 'Simpan BOM'],
            ['route_name' => 'bom.show', 'deskripsi' => 'Detail BOM'],
            ['route_name' => 'bom.edit', 'deskripsi' => 'Edit BOM'],
            ['route_name' => 'bom.update', 'deskripsi' => 'Update BOM'],
            ['route_name' => 'bom.destroy', 'deskripsi' => 'Hapus BOM'],
            
            // Permission Management
            ['route_name' => 'permissions.index', 'deskripsi' => 'Lihat Permission'],
            ['route_name' => 'permissions.store', 'deskripsi' => 'Tambah Permission'],
            ['route_name' => 'permissions.update', 'deskripsi' => 'Edit Permission'],
            ['route_name' => 'permissions.destroy', 'deskripsi' => 'Hapus Permission'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Assign all permissions to Superadmin
        $allPermissions = Permission::all();
        $superadminGroup->permissions()->sync($allPermissions->pluck('id'));

        // Assign some permissions to Produksi
        $produksiPermissions = Permission::whereIn('route_name', [
            'dashboard',
            'bom.index',
            'bom.create',
            'bom.store',
            'bom.show',
            'bom.edit',
            'bom.update',
            'kode-material.index',
            'uom.index',
            'proyek.index',
            'revisi.index'
        ])->get();
        $produksiGroup->permissions()->sync($produksiPermissions->pluck('id'));

        // Assign basic permissions to User
        $basicPermissions = Permission::whereIn('route_name', [
            'dashboard',
            'profile',
            'profile.edit'
        ])->get();
        $userGroup->permissions()->sync($basicPermissions->pluck('id'));

        // Create default users
        User::create([
            'nip' => 'ADMIN001',
            'nama' => 'Super Administrator',
            'posisi' => 'System Administrator',
            'user_group_id' => $superadminGroup->id,
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'nip' => 'PROD001',
            'nama' => 'Manager Produksi',
            'posisi' => 'Production Manager',
            'user_group_id' => $produksiGroup->id,
            'email' => 'produksi@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'nip' => 'USER001',
            'nama' => 'User Biasa',
            'posisi' => 'Staff',
            'user_group_id' => $userGroup->id,
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}