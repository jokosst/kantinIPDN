<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Get all users
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    /**
     * Get single user
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    /**
     * Create new user
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'level' => 'required|in:admin,cashier,manager',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'level' => $request->level,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dibuat',
            'data' => $user,
        ], 201);
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama' => 'string|max:255',
            'username' => 'string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'level' => 'in:admin,cashier,manager',
        ]);

        if ($request->has('password') && $request->password) {
            $request->merge(['password' => Hash::make($request->password)]);
        } else {
            $request->request->remove('password');
        }

        $user->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diupdate',
            'data' => $user,
        ]);
    }

    /**
     * Delete user
     */
    public function destroy($id)
    {
        // Prevent deleting the last admin
        $adminCount = User::where('level', 'admin')->count();
        $user = User::findOrFail($id);

        if ($user->level === 'admin' && $adminCount <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus admin terakhir',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus',
        ]);
    }
}
