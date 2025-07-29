<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return view('permissions.index');
    }

    public function getData()
    {
        try {
            $permissions = Permission::orderBy('route_name', 'asc')->get();
            
            Log::info('Permissions fetched: ' . $permissions->count());
            
            $data = $permissions->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'route_name' => $permission->route_name,
                    'deskripsi' => $permission->deskripsi ?? '-',
                    'created_at' => $permission->created_at ? $permission->created_at->format('d/m/Y H:i') : '-',
                ];
            });
            
            return response()->json([
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in PermissionController::getData: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat mengambil data',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Store permission request: ', $request->all());
            
            $validator = Validator::make($request->all(), [
                'route_name' => 'required|string|unique:permissions,route_name|max:255',
                'deskripsi' => 'nullable|string|max:255'
            ], [
                'route_name.required' => 'Route name harus diisi',
                'route_name.unique' => 'Route name sudah ada',
                'route_name.max' => 'Route name maksimal 255 karakter',
                'deskripsi.max' => 'Deskripsi maksimal 255 karakter'
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

            Log::info('Permission created: ', $permission->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Permission berhasil ditambahkan',
                'permission' => $permission
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in PermissionController::store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            return response()->json($permission);
        } catch (\Exception $e) {
            Log::error('Error in PermissionController::show: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Permission tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $permission = Permission::findOrFail($id);
            
            Log::info('Update permission request: ', $request->all());

            $validator = Validator::make($request->all(), [
                'route_name' => 'required|string|unique:permissions,route_name,' . $id . '|max:255',
                'deskripsi' => 'nullable|string|max:255'
            ], [
                'route_name.required' => 'Route name harus diisi',
                'route_name.unique' => 'Route name sudah ada',
                'route_name.max' => 'Route name maksimal 255 karakter',
                'deskripsi.max' => 'Deskripsi maksimal 255 karakter'
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

            Log::info('Permission updated: ', $permission->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Permission berhasil diupdate',
                'permission' => $permission
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in PermissionController::update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            
            // Cek apakah permission sedang digunakan
            if ($permission->userGroups()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permission tidak dapat dihapus karena sedang digunakan oleh user group'
                ], 422);
            }
            
            $permission->delete();
            
            Log::info('Permission deleted: ' . $id);

            return response()->json([
                'success' => true,
                'message' => 'Permission berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in PermissionController::destroy: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}