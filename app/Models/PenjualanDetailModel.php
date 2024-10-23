<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenjualanDetailModel extends Model
{
    use HasFactory;

    // Tentukan tabel yang digunakan oleh model ini
    protected $table = 't_penjualan_detail';

    // Tentukan primary key dari tabel
    protected $primaryKey = 'detail_id';

    // Tentukan kolom-kolom yang bisa diisi secara massal
    protected $fillable = ['penjualan_id', 'barang_id', 'harga', 'jumlah'];

    // Relasi dengan tabel t_penjualan
    public function penjualan(): BelongsTo {
        return $this->belongsTo(PenjualanModel::class, 'penjualan_id', 'penjualan_id');
    }

    // Relasi dengan tabel m_barang
    public function barang(): BelongsTo {
        return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id');
    }

    // Setter untuk otomatis menghitung harga (harga_jual * jumlah)
    public function setHargaAttribute($value)
    {
        // Ambil data barang berdasarkan barang_id
        $barang = $this->barang()->first();
        
        if ($barang) {
            // Jika barang ditemukan, kalikan harga_jual dengan jumlah
            $this->attributes['harga'] = $barang->harga_jual * $this->attributes['jumlah'];
        } else {
            // Jika tidak ditemukan, masukkan harga manual (misalnya saat seeding atau pengisian langsung)
            $this->attributes['harga'] = $value;
        }
    }
}
