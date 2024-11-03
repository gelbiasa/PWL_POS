<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModel;

class UserController extends Controller
{
    public function index()
    {
        return UserModel::all();
    }

    public function store(Request $request)
    {
        // Handle the image upload, if provided
        $filename = null;
        if ($request->hasFile('foto_profil')) {
            $foto_profil = $request->file('foto_profil');
            $path = $foto_profil->storeAs('public/gambar', $foto_profil->hashName());
            $filename = basename($path);
        }

        // Create user with the uploaded or null foto_profil
        $user = UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id,
            'foto_profil' => $filename
        ]);

        return response()->json($user, 201);
    }

    public function show(UserModel $user)
    {
        return UserModel::find($user->user_id);
    }

    public function update(Request $request, UserModel $user)
    {
        // Handle the image upload, if provided
        $filename = $user->foto_profil; // Retain existing photo if not updated
        if ($request->hasFile('foto_profil')) {
            $foto_profil = $request->file('foto_profil');
            $path = $foto_profil->storeAs('public/gambar', $foto_profil->hashName());
            $filename = basename($path);
        }

        // Update user with the provided data, keeping others unchanged
        $user->fill([
            'username' => $request->username ?? $user->username,
            'nama' => $request->nama ?? $user->nama,
            'password' => $request->filled('password') ? bcrypt($request->password) : $user->password,
            'level_id' => $request->level_id ?? $user->level_id,
            'foto_profil' => $filename
        ]);

        $user->save();

        return response()->json($user);
    }


    public function destroy(UserModel $user)
    {
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus',
        ]);
    }
}
