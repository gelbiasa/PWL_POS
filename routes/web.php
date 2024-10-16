<?php

use App\Http\Controllers\barangcontroller;
use App\Http\Controllers\kategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\userController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AuthController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use Monolog\Level;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/level', [LevelController::class, 'index']);
// Route::get('/kategori',[kategoriController::class, 'index']);
// Route::get('/user',[userController::class, 'index']);

// Route::get('/barang',[barangcontroller::class, 'index']);

// Route::get('/user/tambah', [userController::class, 'tambah']);

// Route::post('/user/tambah_simpan', [userController::class, 'tambah_simpan']);

// Route::get('/user/ubah/{id}', [userController::class, 'ubah']);

// Route::put('/user/ubah_simpan/{id}', [userController::class, 'ubah_simpan']);

// Route::get('/user/hapus/{id}', [userController::class, 'hapus']);

Route::pattern('id', '[0-9]+'); // Artinya: Ketika ada parameter {id}, maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'postRegister']);

// Group route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    Route::get('/', [WelcomeController::class, 'index']);

    Route::group(['prefix' =>'user', 'middleware' => 'authorize:ADM'],function(){
        Route::get('/', [UserController :: class, 'index' ]);         // menampilkan halaman awal user
        Route::post('/list', [UserController :: class, 'list' ]);     // menampilkan data user dalam bentuk json untuk datables
        Route::get('/create', [UserController :: class, 'create' ]);  // menampilkan halaman form tambah user
        Route::post('/', [UserController :: class, 'store' ]);        // menyimpan data user baru
        Route::get('/create_ajax', [UserController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/ajax', [UserController::class, 'store_ajax']);     // Menyimpan data user baru Ajax
        Route::get('/{id}', [UserController :: class, 'show']);       // menampilkan detail user
        Route::get('/{id}/show_ajax', [UserController :: class, 'show_ajax']);       // menampilkan detail user
        Route::get('/{id}/edit', [UserController :: class, 'edit' ]); // menampilkan halaman form edit user
        Route::put('/{id}', [UserController :: class, 'update']);     // menyimpan perubahan data user
        Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // Menampilkan halaman form edit user Ajax
        Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); // Menyimpan perubahan data user Ajax
        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // Untuk menampilkan form konfirmasi delete user Ajax
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Untuk menghapus data user Ajax
        Route::delete('/{id}', [UserController :: class, 'destroy']); // menghapus data user
        Route::get('/import', [UserController::class, 'import']);     
        Route::post('/import_ajax', [UserController::class, 'import_ajax']);
        Route::get('/export_excel', [UserController::class, 'export_excel']);
        Route::get('/export_pdf', [UserController::class, 'export_pdf']);    
    });
    
    Route::group(['prefix' =>'level', 'middleware' => 'authorize:ADM'],function(){
        Route::get('/', [LevelController::class, 'index']);         // menampilkan halaman awal level
        Route::post('/list', [LevelController::class, 'list']);     // menampilkan data level dalam bentuk json untuk datatables
        Route::get('/create', [LevelController::class, 'create']);  // menampilkan halaman form tambah level
        Route::post('/', [LevelController::class, 'store']);        // menyimpan data level baru
        Route::get('/create_ajax', [LevelController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/ajax', [LevelController::class, 'store_ajax']);     // Menyimpan data user baru Ajax
        Route::get('/{id}', [LevelController::class, 'show']);      // menampilkan detail level
        Route::get('/{id}/show_ajax', [LevelController :: class, 'show_ajax']);
        Route::get('/{id}/edit', [LevelController::class, 'edit']); // menampilkan halaman form edit level
        Route::put('/{id}', [LevelController::class, 'update']);    // menyimpan perubahan data level
        Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); // Menampilkan halaman form edit level Ajax
        Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']); // Menyimpan perubahan data level Ajax
        Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']); // Untuk menampilkan form konfirmasi delete level Ajax
        Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); // Untuk menghapus data level Ajax
        Route::delete('/{id}', [LevelController::class, 'destroy']); // menghapus data level
        Route::get('/import', [LevelController::class, 'import']);     
        Route::post('/import_ajax', [LevelController::class, 'import_ajax']);
        Route::get('/export_excel', [LevelController::class, 'export_excel']); 
        Route::get('/export_pdf', [LevelController::class, 'export_pdf']);   
    });
    
    Route::group(['prefix' =>'kategori', 'middleware' => ['authorize:ADM,MNG']],function(){
        Route::get('/', [KategoriController::class, 'index']);
        Route::post('/list', [KategoriController::class, 'list']);
        Route::get('/create', [KategoriController::class, 'create']);
        Route::post('/', [KategoriController::class, 'store']);
        Route::get('/create_ajax', [KategoriController::class, 'create_ajax']); 
        Route::post('/ajax', [KategoriController::class, 'store_ajax']);     
        Route::get('/{id}', [KategoriController::class, 'show']);
        Route::get('/{id}/show_ajax', [KategoriController :: class, 'show_ajax']);
        Route::get('/{id}/edit', [KategoriController::class, 'edit']);
        Route::put('/{id}', [KategoriController::class, 'update']);
        Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); 
        Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']); 
        Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']); 
        Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); 
        Route::delete('/{id}', [KategoriController::class, 'destroy']);
        Route::get('/import', [KategoriController::class, 'import']);     
        Route::post('/import_ajax', [KategoriController::class, 'import_ajax']);  
        Route::get('/export_excel', [KategoriController::class, 'export_excel']); 
        Route::get('/export_pdf', [KategoriController::class, 'export_pdf']);   
    });
    
    Route::group(['prefix' =>'barang', 'middleware' => ['authorize:ADM,MNG']],function(){
        Route::get('/', [barangController::class, 'index']);              // menampilkan halaman awal barang
        Route::post('/list', [barangController::class, 'list']);          // menampilkan data barang dalam bentuk json untuk datatables
        Route::get('/create', [barangController::class, 'create']);       // menampilkan halaman form tambah barang
        Route::post('/', [barangController::class, 'store']);  
        Route::get('/create_ajax', [barangController::class, 'create_ajax']); 
        Route::post('/ajax', [barangController::class, 'store_ajax']);                 // menyimpan data barang baru
        Route::get('/{id}', [barangController::class, 'show']);            // menampilkan detail barang
        Route::get('/{id}/show_ajax', [barangController :: class, 'show_ajax']);
        Route::get('/{id}/edit', [barangController::class, 'edit']);       // menampilkan halaman form edit barang
        Route::put('/{id}', [barangController::class, 'update']);          // menyimpan perubahan data barang
        Route::get('/{id}/edit_ajax', [barangController::class, 'edit_ajax']); 
        Route::put('/{id}/update_ajax', [barangController::class, 'update_ajax']); 
        Route::get('/{id}/delete_ajax', [barangController::class, 'confirm_ajax']); 
        Route::delete('/{id}/delete_ajax', [barangController::class, 'delete_ajax']); 
        Route::get('/import', [barangController::class, 'import']);     
        Route::post('/import_ajax', [barangController::class, 'import_ajax']); 
        Route::get('/export_excel', [barangController::class, 'export_excel']);   
        Route::get('/export_pdf', [barangController::class, 'export_pdf']);   
    });
    
    Route::group(['prefix' =>'supplier', 'middleware' => ['authorize:ADM,MNG,STF']],function(){
        Route::get('/', [SupplierController::class, 'index']);              // menampilkan halaman awal supplier
        Route::post('/list', [SupplierController::class, 'list']);          // menampilkan data supplier dalam bentuk json untuk datatables
        Route::get('/create', [SupplierController::class, 'create']);       // menampilkan halaman form tambah supplier
        Route::post('/', [SupplierController::class, 'store']);              // menyimpan data supplier baru
        Route::get('/create_ajax', [SupplierController::class, 'create_ajax']); 
        Route::post('/ajax', [SupplierController::class, 'store_ajax']);
        Route::get('/{id}', [SupplierController::class, 'show']);            // menampilkan detail supplier
        Route::get('/{id}/show_ajax', [SupplierController :: class, 'show_ajax']);
        Route::get('/{id}/edit', [SupplierController::class, 'edit']);       // menampilkan halaman form edit supplier
        Route::put('/{id}', [SupplierController::class, 'update']);          // menyimpan perubahan data supplier
        Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); 
        Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']); 
        Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); 
        Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); 
        Route::delete('/{id}', [SupplierController::class, 'destroy']);      // menghapus data supplier
        Route::get('/import', [SupplierController::class, 'import']);     
        Route::post('/import_ajax', [SupplierController::class, 'import_ajax']);
        Route::get('/export_excel', [SupplierController::class, 'export_excel']); 
        Route::get('/export_pdf', [SupplierController::class, 'export_pdf']); 
    });
    
    Route::group(['prefix' => 'stok'], function () {
        Route::get('/', [StokController::class, 'index']);              // Menampilkan halaman awal stok
        Route::post('/list', [StokController::class, 'list']);          // Menampilkan data stok dalam bentuk JSON untuk datatables
        Route::get('/create', [StokController::class, 'create']);       // Menampilkan halaman form tambah stok
        Route::post('/', [StokController::class, 'store']);             // Menyimpan data stok baru
        Route::get('/{id}', [StokController::class, 'show']);           // Menampilkan detail stok
        Route::get('/{id}/edit', [StokController::class, 'edit']);      // Menampilkan halaman form edit stok
        Route::put('/{id}', [StokController::class, 'update']);         // Menyimpan perubahan data stok
        Route::delete('/{id}', [StokController::class, 'destroy']);     // Menghapus data stok
    });
});
