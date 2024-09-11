<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class barangcontroller extends Controller
{
    public function index(){
        $row = DB::update('update m_barang set harga_beli = ?, harga_jual=? where barang_id = ? ', [8000, 10000, 1]);
        return 'Data berhasil di update, jumlah data yg diupdate '.$row;
    }
}
