<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\UserModel;

class ProfileController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Profil',
            'list' => ['Home', 'Profile']
        ];

        $page = (object) [
            'title' => 'Data Profil Pengguna'
        ];

        $activeMenu = 'profile'; // Set the active menu

        return view('profile.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function update_profile(Request $request)
    {
        $request->validate([
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Validasi file gambar
        ]);

        // Mendapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Mengambil pengguna berdasarkan ID menggunakan UserModel
        $user = UserModel::find($userId);

        // Jika ada file gambar yang diupload
        if ($request->hasFile('foto_profil')) {
            // Hapus foto profil lama jika ada
            if ($user->foto_profil && Storage::exists('public/' . $user->foto_profil)) {
                Storage::delete('public/' . $user->foto_profil);
            }

            // Simpan foto profil baru
            $path = $request->file('foto_profil')->store('gambar', 'public');
            $user->foto_profil = $path;
        }

        // Simpan perubahan ke database
        $user->save();

        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui');
    }
}