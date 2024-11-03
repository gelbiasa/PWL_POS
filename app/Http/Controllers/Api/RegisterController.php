<?php

namespace App\Http\Controllers\Api;

use App\Models\UserModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        // Set validation
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'nama' => 'required',
            'password' => 'required|min:5|confirmed',
            'level_id' => 'required',
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120'
        ]);

        // If validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle the image upload and keep original name
        if ($request->hasFile('foto_profil')) {

            $foto_profil = $request->file('foto_profil');
            
            $path = $request->file('foto_profil')->storeAs('public/gambar', $foto_profil->hashName());
            
            $filename = basename($path);
        }

        // Create user
        $user = UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id,
            'foto_profil' => $filename ?? null
        ]);

        // Return response JSON if user is created
        if ($user) {
            return response()->json([
                'user' => $user,
                'success' => true,
            ], 201);
        }

        // Return JSON process insert failed
        return response()->json([
            'success' => false,
        ], 409);
    }
}