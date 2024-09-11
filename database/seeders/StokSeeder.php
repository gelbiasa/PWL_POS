<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'stok_id' => 1,
                'supplier_id' => 1,
                'barang_id' => 1,
                'user_id' => 3,
                'stok_tanggal' => '2024-5-27',
                'stok_jumlah' => 15,
            ],
            [
                'stok_id' => 2,
                'supplier_id' => 1,
                'barang_id' => 2,
                'user_id' => 3,
                'stok_tanggal' => '2024-5-27',
                'stok_jumlah' => 15,
            ],
            [
                'stok_id' => 3,
                'supplier_id' => 1,
                'barang_id' => 3,
                'user_id' => 3,
                'stok_tanggal' => '2024-5-27',
                'stok_jumlah' => 30,
            ],
            [
                'stok_id' => 4,
                'supplier_id' => 1,
                'barang_id' => 4,
                'user_id' => 3,
                'stok_tanggal' => '2024-5-27',
                'stok_jumlah' => 30,
            ],
            [
                'stok_id' => 5,
                'supplier_id' => 1,
                'barang_id' => 5,
                'user_id' => 3,
                'stok_tanggal' => '2024-5-27',
                'stok_jumlah' => 30,
            ],
            [
                'stok_id' => 6,
                'supplier_id' => 2,
                'barang_id' => 6,
                'user_id' => 3,
                'stok_tanggal' => '2024-5-27',
                'stok_jumlah' => 50,
            ],
            [
                'stok_id' => 7,
                'supplier_id' => 2,
                'barang_id' => 7,
                'user_id' => 3,
                'stok_tanggal' => '2024-5-27',
                'stok_jumlah' => 10,
            ],
            [
                'stok_id' => 8,
                'supplier_id' => 2,
                'barang_id' => 8,
                'user_id' => 3,
                'stok_tanggal' => '2024-5-27',
                'stok_jumlah' => 8,
            ],
            [
                'stok_id' => 9,
                'supplier_id' => 2,
                'barang_id' => 9,
                'user_id' => 3,
                'stok_tanggal' => '2024-5-27',
                'stok_jumlah' => 5,
            ],
            [
                'stok_id' => 10,
                'supplier_id' => 2,
                'barang_id' => 10,
                'user_id' => 3,
                'stok_tanggal' => '2024-5-27',
                'stok_jumlah' => 10,
            ],
            [
                'stok_id' => 11,
                'supplier_id' => 3,
                'barang_id' => 11,
                'user_id' => 3,
                'stok_tanggal' => '2024-5-27',
                'stok_jumlah' => 15,
            ],
            [
                'stok_id' => 12,
                'supplier_id' => 3,
                'barang_id' => 12,
                'user_id' => 3,
                'stok_tanggal' => '2024-5-27',
                'stok_jumlah' => 10,
            ],
            [
                'stok_id' => 13,
                'supplier_id' => 3,
                'barang_id' => 13,
                'user_id' => 3,
                'stok_tanggal' => '2024-5-27',
                'stok_jumlah' => 10,
            ],
            [
                'stok_id' => 14,
                'supplier_id' =>3,
                'barang_id' => 14,
                'user_id' => 3,
                'stok_tanggal' => '2024-5-27',
                'stok_jumlah' => 15,
            ],
            [
                'stok_id' => 15,
                'supplier_id' => 3,
                'barang_id' => 15,
                'user_id' => 3,
                'stok_tanggal' => '2024-5-27',
                'stok_jumlah' => 10,
            ],

        ];    
        DB::table('t_stok')->insert($data);
    }
}
