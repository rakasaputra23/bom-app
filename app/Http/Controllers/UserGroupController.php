<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UserGroup;
use App\Models\Permission;

class UserGroupController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('user.user-group', compact('permissions'));
    }

    public function getData()
    {
        $userGroups = UserGroup::withCount('users')->get();
        
        return response()->json([
            'data' => $userGroups->map(function ($group) {
                return [
                    'id' => $group->id,
                    'nama' => $group->nama,
                    'users_count' => $group->users_count,
                    'created_at' => $group->created_at->format('d/m/Y H:i'),
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:user_groups,nama',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $userGroup = UserGroup::create([
            'nama' => $request->nama
        ]);

        if ($request->has('permissions')) {
            $userGroup->permissions()->sync($request->permissions);
        }

        return response()->json([
            'success' => true,
            'message' => 'User Group berhasil ditambahkan',
            'userGroup' => $userGroup->load('permissions')
        ]);
    }

    public function show($id)
    {
        $userGroup = UserGroup::with(['permissions', 'users'])->findOrFail($id);
        return response()->json($userGroup);
    }

    public function update(Request $request, $id)
    {
        $userGroup = UserGroup::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:user_groups,nama,' . $id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $userGroup->update([
            'nama' => $request->nama
        ]);

        if ($request->has('permissions')) {
            $userGroup->permissions()->sync($request->permissions);
        }

        return response()->json([
            'success' => true,
            'message' => 'User Group berhasil diupdate',
            'userGroup' => $userGroup->load('permissions')
        ]);
    }

    public function destroy($id)
    {
        $userGroup = UserGroup::findOrFail($id);
        
        // Check if group has users
        if ($userGroup->users()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus group yang masih memiliki user'
            ], 403);
        }

        $userGroup->delete();

        return response()->json([
            'success' => true,
            'message' => 'User Group berhasil dihapus'
        ]);
    }

    public function getPermissions($id)
    {
        $userGroup = UserGroup::with('permissions')->findOrFail($id);
        return response()->json($userGroup->permissions);
    }
}