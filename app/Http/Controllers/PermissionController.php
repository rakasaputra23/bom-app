<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return view('permissions.index');
    }

    public function getData()
    {
        $permissions = Permission::all();
        
        return response()->json([
            'data' => $permissions->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'route_name' => $permission->route_name,
                    'deskripsi' => $permission->deskripsi ?? '-',
                    'created_at' => $permission->created_at->format('d/m/Y H:i'),
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'route_name' => 'required|string|unique:permissions,route_name',
            'deskripsi' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $permission = Permission::create([
            'route_name' => $request->route_name,
            'deskripsi' => $request->deskripsi
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permission berhasil ditambahkan',
            'permission' => $permission
        ]);
    }

    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        return response()->json($permission);
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'route_name' => 'required|string|unique:permissions,route_name,' . $id,
            'deskripsi' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $permission->update([
            'route_name' => $request->route_name,
            'deskripsi' => $request->deskripsi
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permission berhasil diupdate',
            'permission' => $permission
        ]);
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Permission berhasil dihapus'
        ]);
    }
}