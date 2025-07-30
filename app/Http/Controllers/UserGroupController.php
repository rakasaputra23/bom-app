<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UserGroup;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

            $permissions = $request->input('permissions', []);
            if (!is_array($permissions)) {
                $permissions = [];
            }
            
            $validPermissions = array_filter($permissions, function($id) {
                return is_numeric($id) && Permission::where('id', $id)->exists();
            });
            
            $userGroup->permissions()->sync($validPermissions);

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
     * Update user group - DENGAN FORCE REFRESH USER RELATIONS
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
            $userGroup->update([
                'nama' => trim($request->nama)
            ]);

            $permissions = $request->input('permissions', []);
            if (!is_array($permissions)) {
                $permissions = [];
            }
            
            $validPermissions = array_filter($permissions, function($id) {
                return is_numeric($id) && Permission::where('id', $id)->exists();
            });
            
            $userGroup->permissions()->sync($validPermissions);

            // PERBAIKAN KRITIS: Force refresh semua user dalam grup ini
            $this->forceRefreshUsersInGroup($userGroup->id);

            DB::commit();

            Log::info('User Group updated successfully', [
                'group_id' => $userGroup->id,
                'group_name' => $userGroup->nama,
                'permissions_count' => count($validPermissions),
                'users_refreshed' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User Group berhasil diupdate',
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
            
            $userGroup->permissions()->detach();
            $userGroup->delete();

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
     * PERBAIKAN KRITIS: Force refresh semua user dalam group
     */
    private function forceRefreshUsersInGroup($groupId)
    {
        try {
            // Ambil semua user dalam group ini dan force refresh relations mereka
            $users = User::where('user_group_id', $groupId)->get();
            
            foreach ($users as $user) {
                // Unload existing relationships
                unset($user->relations['group']);
                
                // Load fresh dengan permissions
                $user->load('group.permissions');
                
                Log::info('User relations refreshed', [
                    'user_id' => $user->id,
                    'group_id' => $groupId
                ]);
            }

            Log::info("Successfully refreshed {$users->count()} users in group {$groupId}");

        } catch (Exception $e) {
            Log::error("Error refreshing users in group {$groupId}: " . $e->getMessage());
        }
    }
}