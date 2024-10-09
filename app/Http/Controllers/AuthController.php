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
        if(Auth::check()){ // jika sudah login, maka redirect ke halaman home 
            return redirect('/'); 
        } 
        return view('auth.login'); 
    } 
 
    public function postlogin(Request $request) 
    { 
        if($request->ajax() || $request->wantsJson()){ 
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
            'password' => 'required|min:6|max:20',
            'level_id' => 'required' // Validate level_id
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id, // Save the selected level
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Register Berhasil',
            'redirect' => url('login')
        ]);
    }
}