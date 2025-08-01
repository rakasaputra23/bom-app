<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate tables
        DB::table('group_permissions')->truncate();
        Permission::truncate();
        User::truncate();
        UserGroup::truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create User Groups
        $superadminGroup = UserGroup::create(['nama' => 'Superadmin']);
        $produksiGroup = UserGroup::create(['nama' => 'Produksi']);
        $userGroup = UserGroup::create(['nama' => 'User']);

        // PERBAIKAN: Permissions yang lebih lengkap dan konsisten
        $permissions = [
            // Dashboard & Profile
            ['route_name' => 'dashboard', 'deskripsi' => 'Akses Dashboard'],
            ['route_name' => 'profile', 'deskripsi' => 'Lihat Profile'],
            ['route_name' => 'profile.edit', 'deskripsi' => 'Edit Profile'],
            
            // User Management
            ['route_name' => 'user', 'deskripsi' => 'Lihat Daftar User'],
            ['route_name' => 'user.getData', 'deskripsi' => 'Get Data User'],
            ['route_name' => 'user.store', 'deskripsi' => 'Tambah User Baru'],
            ['route_name' => 'user.show', 'deskripsi' => 'Lihat Detail User'],
            ['route_name' => 'user.update', 'deskripsi' => 'Edit User'],
            ['route_name' => 'user.destroy', 'deskripsi' => 'Hapus User'],
            
            // User Group Management
            ['route_name' => 'user.group', 'deskripsi' => 'Lihat Daftar User Group'],
            ['route_name' => 'user.group.getData', 'deskripsi' => 'Get Data User Group'],
            ['route_name' => 'user.group.store', 'deskripsi' => 'Tambah User Group'],
            ['route_name' => 'user.group.show', 'deskripsi' => 'Lihat Detail User Group'],
            ['route_name' => 'user.group.update', 'deskripsi' => 'Edit User Group'],
            ['route_name' => 'user.group.destroy', 'deskripsi' => 'Hapus User Group'],
            ['route_name' => 'user.group.permissions', 'deskripsi' => 'Lihat Permission User Group'],
            
            // Permission Management
            ['route_name' => 'permissions.index', 'deskripsi' => 'Lihat Permission'],
            ['route_name' => 'permissions.getData', 'deskripsi' => 'Get Data Permission'],
            ['route_name' => 'permissions.store', 'deskripsi' => 'Tambah Permission'],
            ['route_name' => 'permissions.show', 'deskripsi' => 'Lihat Detail Permission'],
            ['route_name' => 'permissions.update', 'deskripsi' => 'Edit Permission'],
            ['route_name' => 'permissions.destroy', 'deskripsi' => 'Hapus Permission'],
            
            // Master Data - Kode Material
            ['route_name' => 'kode-material.index', 'deskripsi' => 'Lihat Kode Material'],
            ['route_name' => 'kode-material.getData', 'deskripsi' => 'Get Data Kode Material'],
            ['route_name' => 'kode-material.store', 'deskripsi' => 'Tambah Kode Material'],
            ['route_name' => 'kode-material.show', 'deskripsi' => 'Lihat Detail Kode Material'],
            ['route_name' => 'kode-material.update', 'deskripsi' => 'Edit Kode Material'],
            ['route_name' => 'kode-material.destroy', 'deskripsi' => 'Hapus Kode Material'],
            
            // Master Data - UOM
            ['route_name' => 'uom.index', 'deskripsi' => 'Lihat UOM'],
            ['route_name' => 'uom.getData', 'deskripsi' => 'Get Data UOM'],
            ['route_name' => 'uom.store', 'deskripsi' => 'Tambah UOM'],
            ['route_name' => 'uom.show', 'deskripsi' => 'Lihat Detail UOM'],
            ['route_name' => 'uom.update', 'deskripsi' => 'Edit UOM'],
            ['route_name' => 'uom.destroy', 'deskripsi' => 'Hapus UOM'],
            
            // Master Data - Proyek
            ['route_name' => 'proyek.index', 'deskripsi' => 'Lihat Proyek'],
            ['route_name' => 'proyek.getData', 'deskripsi' => 'Get Data Proyek'],
            ['route_name' => 'proyek.store', 'deskripsi' => 'Tambah Proyek'],
            ['route_name' => 'proyek.show', 'deskripsi' => 'Lihat Detail Proyek'],
            ['route_name' => 'proyek.update', 'deskripsi' => 'Edit Proyek'],
            ['route_name' => 'proyek.destroy', 'deskripsi' => 'Hapus Proyek'],
            
            // Master Data - Revisi
            ['route_name' => 'revisi.index', 'deskripsi' => 'Lihat Revisi'],
            ['route_name' => 'revisi.getData', 'deskripsi' => 'Get Data Revisi'],
            ['route_name' => 'revisi.store', 'deskripsi' => 'Tambah Revisi'],
            ['route_name' => 'revisi.show', 'deskripsi' => 'Lihat Detail Revisi'],
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
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Assign all permissions to Superadmin
        $allPermissions = Permission::all();
        $superadminGroup->permissions()->sync($allPermissions->pluck('id'));

        // PERBAIKAN: Assign permissions ke Produksi (termasuk semua route BOM dan master data)
        $produksiPermissions = Permission::whereIn('route_name', [
            'dashboard',
            'profile',
            'profile.edit',
            // BOM permissions
            'bom.index',
            'bom.create',
            'bom.store',
            'bom.show',
            'bom.edit',
            'bom.update',
            'bom.destroy',
            // Master data permissions
            'kode-material.index',
            'kode-material.getData',
            'kode-material.show',
            'uom.index',
            'uom.getData',
            'uom.show',
            'proyek.index',
            'proyek.getData', 
            'proyek.show',
            'revisi.index',
            'revisi.getData',
            'revisi.show'
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

        echo "Seeder completed successfully!\n";
        echo "Superadmin permissions: " . $superadminGroup->permissions()->count() . "\n";
        echo "Produksi permissions: " . $produksiGroup->permissions()->count() . "\n";
        echo "User permissions: " . $userGroup->permissions()->count() . "\n";
    }
}