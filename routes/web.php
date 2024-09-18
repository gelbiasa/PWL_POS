<?php

use App\Http\Controllers\barangcontroller;
use App\Http\Controllers\kategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/level', [LevelController::class, 'index']);
Route::get('/kategori',[kategoriController::class, 'index']);
Route::get('/user',[userController::class, 'index']);

Route::get('/barang',[barangcontroller::class, 'index']);

Route::get('/user/tambah', [userController::class, 'tambah']);

Route::post('/user/tambah_simpan', [userController::class, 'tambah_simpan']);

Route::get('/user/ubah/{id}', [userController::class, 'ubah']);

Route::put('/user/ubah_simpan/{id}', [userController::class, 'ubah_simpan']);

Route::get('/user/hapus/{id}', [userController::class, 'hapus']);


