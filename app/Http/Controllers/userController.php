<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    public function index()
    {

        $user = UserModel::create([
            'username' => 'manager11',
            'nama' => 'Manager11',
            'password' => hash::make('12345'),
            'level_id' => 2,
        ],
    );
    $user->username = 'manager12';

    $user->save();

    $user->wasChanged();
    $user->wasChanged('username');
    $user->wasChanged(['username', 'level_id']);
    $user->wasChanged(['nama']);
    dd($user->wasChanged(['nama', 'username']));
    }
}