<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\UserGroup;

class UserController extends Controller
{
    public function index()
    {
        $userGroups = UserGroup::all();
        return view('user.user', compact('userGroups'));
    }

    public function getData()
    {
        $users = User::with('group')->get();
        
        return response()->json([
            'data' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'nip' => $user->nip,
                    'nama' => $user->nama,
                    'posisi' => $user->posisi,
                    'email' => $user->email,
                    'group_nama' => $user->group ? $user->group->nama : '-',
                    'created_at' => $user->created_at->format('d/m/Y H:i'),
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|string|unique:users,nip',
            'nama' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'user_group_id' => 'required|exists:user_groups,id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'posisi' => $request->posisi,
            'user_group_id' => $request->user_group_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan',
            'user' => $user->load('group')
        ]);
    }

    public function show($id)
    {
        $user = User::with('group')->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nip' => 'required|string|unique:users,nip,' . $id,
            'nama' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'user_group_id' => 'required|exists:user_groups,id',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = [
            'nip' => $request->nip,
            'nama' => $request->nama,
            'posisi' => $request->posisi,
            'user_group_id' => $request->user_group_id,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diupdate',
            'user' => $user->load('group')
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus akun sendiri'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus'
        ]);
    }
}