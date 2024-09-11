<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'barang_id' => 1,
                'kategori_id' => 1,
                'barang_kode' => 'NG',
                'barang_nama' => 'Nasi Goreng',
                'harga_beli' => 8000,
                'harga_jual' => 10000,
            ],
            [
                'barang_id' => 2,
                'kategori_id' => 1,
                'barang_kode' => 'MG',
                'barang_nama' => 'Mie Goreng',
                'harga_beli' => 8000,
                'harga_jual' => 10000,
            ],
            [
                'barang_id' => 3,
                'kategori_id' => 1,
                'barang_kode' => 'AG',
                'barang_nama' => 'Ayam Goreng',
                'harga_beli' => 10000,
                'harga_jual' => 12000,
            ],
            [
                'barang_id' => 4,
                'kategori_id' => 2,
                'barang_kode' => 'CC',
                'barang_nama' => 'Coca Cola',
                'harga_beli' => 5000,
                'harga_jual' => 7000,
            ],
            [
                'barang_id' => 5,
                'kategori_id' => 2,
                'barang_kode' => 'TP',
                'barang_nama' => 'Teh Pucuk',
                'harga_beli' => 4000,
                'harga_jual' => 5000,
            ],
            [
                'barang_id' => 6,
                'kategori_id' => 2,
                'barang_kode' => 'AM',
                'barang_nama' => 'Air Mineral',
                'harga_beli' => 3000,
                'harga_jual' => 4000,
            ],
            [
                'barang_id' => 7,
                'kategori_id' => 3,
                'barang_kode' => 'HP',
                'barang_nama' => 'HandPhone',
                'harga_beli' => 1000000,
                'harga_jual' => 1200000,
            ],
            [
                'barang_id' => 8,
                'kategori_id' => 3,
                'barang_kode' => 'LP',
                'barang_nama' => 'Laptop',
                'harga_beli' => 1500000,
                'harga_jual' => 1700000,
            ],
            [
                'barang_id' => 9,
                'kategori_id' => 3,
                'barang_kode' => 'TB',
                'barang_nama' => 'Tablet',
                'harga_beli' => 1000000,
                'harga_jual' => 1200000,
            ],
            [
                'barang_id' => 10,
                'kategori_id' => 4,
                'barang_kode' => 'LV',
                'barang_nama' => 'Levis',
                'harga_beli' => 300000,
                'harga_jual' => 400000,
            ],
            [
                'barang_id' => 11,
                'kategori_id' => 4,
                'barang_kode' => 'CD',
                'barang_nama' => 'Crocodile',
                'harga_beli' => 300000,
                'harga_jual' => 400000,
            ],
            [
                'barang_id' => 12,
                'kategori_id' => 4,
                'barang_kode' => 'UQ',
                'barang_nama' => 'Uniqlo',
                'harga_beli' => 400000,
                'harga_jual' => 500000,
            ],
            [
                'barang_id' => 13,
                'kategori_id' => 5,
                'barang_kode' => 'ZR',
                'barang_nama' => 'Zara',
                'harga_beli' => 400000,
                'harga_jual' => 500000,
            ],
            [
                'barang_id' => 14,
                'kategori_id' => 5,
                'barang_kode' => 'GS',
                'barang_nama' => 'Guess',
                'harga_beli' => 400000,
                'harga_jual' => 500000,
            ],
            [
                'barang_id' => 15,
                'kategori_id' => 5,
                'barang_kode' => 'CV',
                'barang_nama' => 'Carvil',
                'harga_beli' => 200000,
                'harga_jual' => 300000,
            ],

        ];    
        DB::table('m_barang')->insert($data);
    }
}
