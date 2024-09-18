<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    public function index()
    {

        $user = UserModel::where('level_id', 2)->count();
        return view('user', ['data' => $user]);
    }
}