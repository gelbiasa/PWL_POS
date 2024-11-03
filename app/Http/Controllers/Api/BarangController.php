<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangModel;
use Illuminate\Support\Facades\Storage; 

class BarangController extends Controller
{
    public function index()
    {
        $barangs = BarangModel::all();

        // Modify each item to include full image URL
        $barangs->transform(function ($barang) {
            if ($barang->image) {
                $barang->image_url = Storage::url('public/gambar/' . $barang->image);
            } else {
                $barang->image_url = null;
            }
            return $barang;
        });

        return response()->json($barangs);
    }

    public function store(Request $request)
    {
        // Handle the image upload, if provided
        $filename = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->storeAs('public/gambar', $image->hashName());
            $filename = basename($path);
        }

        // Create barang with the uploaded or null image
        $barang = BarangModel::create([
            'kategori_id' => $request->kategori_id,
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'image' => $filename
        ]);

        return response()->json($barang, 201);
    }

    public function show(BarangModel $barang)
    {
        // Include full image URL for single item
        if ($barang->image) {
            $barang->image_url = Storage::url('public/gambar/' . $barang->image);
        } else {
            $barang->image_url = null;
        }

        return response()->json($barang);
    }

    public function update(Request $request, BarangModel $barang)
    {
        // Handle the image upload, if provided
        $filename = $barang->image; // Retain existing image if not updated
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->storeAs('public/gambar', $image->hashName());
            $filename = basename($path);
        }

        // Update barang with the provided data, keeping others unchanged
        $barang->fill([
            'kategori_id' => $request->kategori_id ?? $barang->kategori_id,
            'barang_kode' => $request->barang_kode ?? $barang->barang_kode,
            'barang_nama' => $request->barang_nama ?? $barang->barang_nama,
            'harga_beli' => $request->harga_beli ?? $barang->harga_beli,
            'harga_jual' => $request->harga_jual ?? $barang->harga_jual,
            'image' => $filename
        ]);

        $barang->save();

        return response()->json($barang);
    }

    public function destroy(BarangModel $barang)
    {
        $barang->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus',
        ]);
    }
}
