<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;
use App\Models\UserModel;
use App\Models\LevelModel;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) { // jika sudah login, maka redirect ke halaman home 
            return redirect('/');
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');

            if (Auth::attempt($credentials)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Login Gagal'
            ]);
        }

        return redirect('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
    public function register()
    {
        $level = LevelModel::all(); // Fetch level from database
        return view('auth.register', compact('level')); // Pass levels to the view
    }

    public function postRegister(Request $request)
    {
        $request->validate([
            'username' => 'required|min:4|max:20',
            'nama' => 'required|min:2|max:50',
            'password' => 'required|min:5|max:20|confirmed', // Password confirmation
            'password_confirmation' => 'required', // Ensure password confirmation field is required
            'level_id' => 'required'
        ], [
            'password.min' => 'Password minimal harus 5 karakter', // Custom error message for password length
            'password.confirmed' => 'Verifikasi password yang anda masukkan tidak sesuai dengan password baru', // Custom error message for confirmation
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Register Berhasil',
            'redirect' => url('login')
        ]);
    }
}
