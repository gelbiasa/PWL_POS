<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'penjualan_id' => 1,
                'user_id' => 3,
                'pembeli' => 'Syffa',
                'penjualan_kode' => 'P0001',
                'penjualan_tanggal' => '2024-5-27',
            ],
            [
                'penjualan_id' => 2,
                'user_id' => 3,
                'pembeli' => 'Syffa',
                'penjualan_kode' => 'P0002',
                'penjualan_tanggal' => '2024-5-27',
            ],
            [
                'penjualan_id' => 3,
                'user_id' => 3,
                'pembeli' => 'Syffa',
                'penjualan_kode' => 'P0003',
                'penjualan_tanggal' => '2024-5-27',
            ],
            [
                'penjualan_id' => 4,
                'user_id' => 3,
                'pembeli' => 'Syffa',
                'penjualan_kode' => 'P0004',
                'penjualan_tanggal' => '2024-5-27',
            ],
            [
                'penjualan_id' => 5,
                'user_id' => 3,
                'pembeli' => 'Syffa',
                'penjualan_kode' => 'P0005',
                'penjualan_tanggal' => '2024-5-27',
            ],
            [
                'penjualan_id' => 6,
                'user_id' => 3,
                'pembeli' => 'Ivan',
                'penjualan_kode' => 'P0006',
                'penjualan_tanggal' => '2024-5-30',
            ],
            [
                'penjualan_id' => 7,
                'user_id' => 3,
                'pembeli' => 'Ivan',
                'penjualan_kode' => 'P0007',
                'penjualan_tanggal' => '2024-5-30',
            ],
            [
                'penjualan_id' => 8,
                'user_id' => 3,
                'pembeli' => 'Ivan',
                'penjualan_kode' => 'P0008',
                'penjualan_tanggal' => '2024-5-30',
            ],
            [
                'penjualan_id' => 9,
                'user_id' => 3,
                'pembeli' => 'Ivan',
                'penjualan_kode' => 'P0009',
                'penjualan_tanggal' => '2024-5-30',
            ],
            [
                'penjualan_id' => 10,
                'user_id' => 3,
                'pembeli' => 'Ivan',
                'penjualan_kode' => 'P00010',
                'penjualan_tanggal' => '2024-5-30',
            ],

        ];    
        DB::table('t_penjualan')->insert($data);
    }
}
