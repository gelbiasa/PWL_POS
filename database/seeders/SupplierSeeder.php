<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'supplier_id' => 1,
                'supplier_kode' =>'YDH',
                'supplier_nama' => 'Yudha',
                'supplier_alamat' => 'Jl. Kalpataru'
            ],
            [
                'supplier_id' => 2,
                'supplier_kode' =>'SKN',
                'supplier_nama' => 'Solikhin',
                'supplier_alamat' => 'Jl. Tunggulwulung'
            ],
            [
                'supplier_id' => 3,
                'supplier_kode' =>'TPK',
                'supplier_nama' => 'Taufik',
                'supplier_alamat' => 'Jl. Pisang Kipas'
            ],
        ];    
        DB::table('m_supplier')->insert($data);
    }
}
