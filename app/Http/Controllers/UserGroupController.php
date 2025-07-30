<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UserGroup;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class UserGroupController extends Controller
{
    /**
     * Menampilkan halaman index user groups
     */
    public function index()
    {
        $permissions = Permission::all();
        return view('user.user-group', compact('permissions'));
    }

    /**
     * Mengambil data user groups untuk DataTable
     */
    public function getData()
    {
        try {
            $userGroups = UserGroup::withCount('users')->get();
            
            return response()->json([
                'data' => $userGroups->map(function ($group) {
                    return [
                        'id' => $group->id,
                        'nama' => $group->nama ?? 'Tidak ada nama',
                        'users_count' => $group->users_count ?? 0,
                        // Format tanggal ke ISO string (YYYY-MM-DD HH:MM:SS)
                        'created_at' => $group->created_at ? $group->created_at->toISOString() : '-',
                    ];
                })
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching user groups data: ' . $e->getMessage());
            return response()->json([
                'data' => [],
                'error' => 'Terjadi kesalahan saat mengambil data'
            ], 500);
        }
    }

    /**
     * Menyimpan user group baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:user_groups,nama',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ], [
            'nama.required' => 'Nama user group wajib diisi',
            'nama.unique' => 'Nama user group sudah digunakan',
            'permissions.array' => 'Format permissions tidak valid',
            'permissions.*.exists' => 'Permission yang dipilih tidak valid'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $userGroup = UserGroup::create([
                'nama' => trim($request->nama)
            ]);

            // Ensure permissions is always an array
            $permissions = $request->input('permissions', []);
            if (!is_array($permissions)) {
                $permissions = [];
            }
            
            $validPermissions = array_filter($permissions, function($id) {
                return is_numeric($id) && Permission::where('id', $id)->exists();
            });
            
            $userGroup->permissions()->sync($validPermissions);

            // Clear general cache untuk jaga-jaga
            $this->clearAllPermissionCache();

            DB::commit();

            Log::info('User group created successfully', [
                'group_id' => $userGroup->id,
                'group_name' => $userGroup->nama,
                'permissions_count' => count($validPermissions)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User Group berhasil ditambahkan',
                'userGroup' => $userGroup->load('permissions')
            ]);

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error creating user group', [
                'error' => $e->getMessage(),
                'input' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan detail user group
     */
    public function show($id)
    {
        try {
            $userGroup = UserGroup::with(['permissions', 'users'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'userGroup' => $userGroup,
                'permissions' => $userGroup->permissions->map(function($permission) {
                    return [
                        'id' => $permission->id,
                        'route_name' => $permission->route_name,
                        'deskripsi' => $permission->deskripsi
                    ];
                }),
                'users' => $userGroup->users->map(function($user) {
                    return [
                        'id' => $user->id,
                        'nama' => $user->nama,
                        'nip' => $user->nip
                    ];
                })
            ]);

        } catch (Exception $e) {
            Log::error('Error showing user group: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'User Group tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update user group - DENGAN CACHE CLEARING YANG DIPERKUAT
     */
    public function update(Request $request, $id)
    {
        try {
            $userGroup = UserGroup::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User Group tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:user_groups,nama,' . $id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ], [
            'nama.required' => 'Nama user group wajib diisi',
            'nama.unique' => 'Nama user group sudah digunakan',
            'permissions.array' => 'Format permissions tidak valid',
            'permissions.*.exists' => 'Permission yang dipilih tidak valid'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // PERBAIKAN 1: Clear cache SEBELUM update untuk memastikan tidak ada cache lama
            $this->clearUserGroupPermissionCache($userGroup->id);

            $userGroup->update([
                'nama' => trim($request->nama)
            ]);

            // Ensure permissions is always an array
            $permissions = $request->input('permissions', []);
            if (!is_array($permissions)) {
                $permissions = [];
            }
            
            $validPermissions = array_filter($permissions, function($id) {
                return is_numeric($id) && Permission::where('id', $id)->exists();
            });
            
            $userGroup->permissions()->sync($validPermissions);

            // PERBAIKAN 2: Clear cache SETELAH update juga untuk memastikan
            $this->clearUserGroupPermissionCache($userGroup->id);
            
            // PERBAIKAN 3: Clear semua cache permission
            $this->clearAllPermissionCache();

            DB::commit();

            Log::info('User Group updated successfully', [
                'group_id' => $userGroup->id,
                'group_name' => $userGroup->nama,
                'permissions_count' => count($validPermissions),
                'cache_cleared' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User Group berhasil diupdate dan cache telah dibersihkan',
                'userGroup' => $userGroup->fresh()->load('permissions')
            ]);

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error updating user group', [
                'group_id' => $id,
                'error' => $e->getMessage(),
                'input' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus user group
     */
    public function destroy($id)
    {
        try {
            $userGroup = UserGroup::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User Group tidak ditemukan'
            ], 404);
        }

        $usersCount = $userGroup->users()->count();
        if ($usersCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "User Group tidak dapat dihapus karena masih digunakan oleh {$usersCount} user"
            ], 422);
        }

        DB::beginTransaction();
        try {
            $groupName = $userGroup->nama;
            
            // Clear cache sebelum hapus
            $this->clearUserGroupPermissionCache($id);
            
            $userGroup->permissions()->detach();
            $userGroup->delete();

            // Clear semua cache setelah hapus
            $this->clearAllPermissionCache();

            DB::commit();

            Log::info('User Group deleted successfully', [
                'group_id' => $id,
                'group_name' => $groupName
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User Group berhasil dihapus'
            ]);

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error deleting user group', [
                'group_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil permissions untuk user group tertentu
     */
    public function getPermissions($id)
    {
        try {
            $userGroup = UserGroup::with('permissions')->findOrFail($id);
            $allPermissions = Permission::orderBy('deskripsi')->get();

            $groupedPermissions = $allPermissions->groupBy(function($permission) {
                $route = $permission->route_name;
                
                if (strpos($route, 'user.group') !== false) return 'User Group Management';
                if (strpos($route, 'user') !== false) return 'User Management';
                if (strpos($route, 'permissions') !== false) return 'Permission Management';
                if (strpos($route, 'bom') !== false) return 'BOM Management';
                if (strpos($route, 'kode-material') !== false) return 'Master Kode Material';
                if (strpos($route, 'uom') !== false) return 'Master UOM';
                if (strpos($route, 'proyek') !== false) return 'Master Proyek';
                if (strpos($route, 'revisi') !== false) return 'Master Revisi';
                if (strpos($route, 'profile') !== false) return 'Profile Management';
                return 'General';
            });

            return response()->json([
                'success' => true,
                'userGroup' => $userGroup,
                'groupedPermissions' => $groupedPermissions,
                'assignedPermissions' => $userGroup->permissions->pluck('id')->toArray()
            ]);

        } catch (Exception $e) {
            Log::error('Error getting permissions for user group', [
                'group_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data permissions'
            ], 500);
        }
    }

    /**
     * PERBAIKAN: Clear cache permissions untuk semua user dalam group - LEBIH COMPREHENSIVE
     */
    private function clearUserGroupPermissionCache($groupId)
    {
        try {
            // Ambil semua user dalam group ini
            $users = User::where('user_group_id', $groupId)->get();
            
            if ($users->isEmpty()) {
                Log::info("No users found in group {$groupId} to clear cache");
                return;
            }

            $affectedUsers = 0;
            // Clear cache untuk setiap user dalam group
            foreach ($users as $user) {
                $this->clearUserPermissionCache($user->id);
                
                // TAMBAHAN: Panggil method clearPermissionCache dari User model juga
                if (method_exists($user, 'clearPermissionCache')) {
                    $user->clearPermissionCache();
                }
                
                $affectedUsers++;
            }

            Log::info("Cache cleared for user group", [
                'group_id' => $groupId,
                'users_affected' => $affectedUsers
            ]);

        } catch (Exception $e) {
            Log::error("Error clearing cache for user group {$groupId}: " . $e->getMessage());
        }
    }

    /**
     * PERBAIKAN: Clear cache permissions untuk user tertentu - LEBIH LENGKAP
     */
    private function clearUserPermissionCache($userId)
    {
        try {
            // Daftar semua route yang mungkin di-cache
            $allRoutes = [
                'dashboard',
                'bom.index', 'bom.create', 'bom.show', 'bom.edit', 'bom.store', 'bom.update', 'bom.destroy',
                'user', 'user.getData', 'user.show', 'user.store', 'user.update', 'user.destroy',
                'user.group', 'user.group.getData', 'user.group.show', 'user.group.store', 'user.group.update', 'user.group.destroy', 'user.group.permissions',
                'permissions.index', 'permissions.getData', 'permissions.show', 'permissions.store', 'permissions.update', 'permissions.destroy',
                'kode-material.index', 'kode-material.getData', 'kode-material.show', 'kode-material.store', 'kode-material.update', 'kode-material.destroy',
                'revisi.index', 'revisi.getData', 'revisi.store', 'revisi.update', 'revisi.destroy',
                'proyek.index', 'proyek.getData', 'proyek.show', 'proyek.store', 'proyek.update', 'proyek.destroy',
                'uom.index', 'uom.getData', 'uom.show', 'uom.store', 'uom.update', 'uom.destroy'
            ];

            $clearedKeys = 0;

            // Clear dengan berbagai pattern cache key
            foreach ($allRoutes as $route) {
                $cacheKeys = [
                    "user_permissions_{$userId}_route_{$route}",
                    "user_{$userId}_has_permission_{$route}",
                    "user_{$userId}_permission_{$route}",
                    "permission_{$userId}_{$route}"
                ];
                
                foreach ($cacheKeys as $key) {
                    if (Cache::forget($key)) {
                        $clearedKeys++;
                    }
                }
            }

            // Clear general user cache patterns
            $generalKeys = [
                "user_permissions_{$userId}",
                "user_{$userId}_permissions",
                "permissions_user_{$userId}",
                "user_group_permissions_{$userId}"
            ];

            foreach ($generalKeys as $key) {
                if (Cache::forget($key)) {
                    $clearedKeys++;
                }
            }

            Log::info("Cache cleared for user", [
                'user_id' => $userId,
                'cache_keys_cleared' => $clearedKeys
            ]);

        } catch (Exception $e) {
            Log::error("Error clearing cache for user {$userId}: " . $e->getMessage());
        }
    }

    /**
     * FUNGSI BARU: Clear semua cache permission - ULTIMATE CLEAR
     */
    private function clearAllPermissionCache()
    {
        try {
            // Method 1: Clear dengan tags jika cache driver mendukung
            if (method_exists(Cache::getStore(), 'tags')) {
                Cache::tags(['user_permissions', 'permissions', 'user_groups'])->flush();
            }

            // Method 2: Clear specific patterns jika menggunakan Redis
            if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
                $redis = Cache::getRedis();
                
                $patterns = [
                    'user_permissions_*',
                    'user_*_has_permission_*',
                    'user_*_permission_*',
                    'permission_*',
                    'user_group_permissions_*'
                ];
                
                foreach ($patterns as $pattern) {
                    $keys = $redis->keys($pattern);
                    if (!empty($keys)) {
                        $redis->del($keys);
                    }
                }
            } else {
                // Method 3: Untuk file cache, clear semua cache yang mungkin
                // Ambil semua user dan clear cache mereka
                $allUsers = User::pluck('id');
                foreach ($allUsers as $userId) {
                    $this->clearUserPermissionCache($userId);
                }
            }

            Log::info("All permission cache cleared successfully");

        } catch (Exception $e) {
            Log::error("Error clearing all permission cache: " . $e->getMessage());
            
            // Fallback: Clear seluruh cache jika metode lain gagal
            try {
                Cache::flush();
                Log::info("Fallback: Entire cache flushed");
            } catch (Exception $fallbackError) {
                Log::error("Fallback cache flush also failed: " . $fallbackError->getMessage());
            }
        }
    }

    /**
     * FUNGSI BARU: Manual endpoint untuk clear cache (untuk debugging)
     */
    public function clearCache(Request $request)
    {
        try {
            if (!auth()->user()->isSuperAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $this->clearAllPermissionCache();

            return response()->json([
                'success' => true,
                'message' => 'All permission cache cleared successfully'
            ]);

        } catch (Exception $e) {
            Log::error('Manual cache clear failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage()
            ], 500);
        }
    }
}