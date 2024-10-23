<?php

namespace App\Http\Controllers;

use App\Models\StokModel;
use App\Models\UserModel;
use App\Models\SupplierModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class StokController extends Controller
{
    // Menampilkan halaman awal stok
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Stok',
            'list' => ['Home', 'Stok']
        ];

        $page = (object) [
            'title' => 'Daftar stok yang tersedia dalam sistem'
        ];

        $users = UserModel::all();
        $suppliers = SupplierModel::all();
        $barang = BarangModel::all();
        $activeMenu = 'stok'; // set menu yang sedang aktif

        return view('stok.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'users' => $users,
            'suppliers' => $suppliers,
            'barang' => $barang,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data stok dalam bentuk json untuk datatables 
    public function list(Request $request)
    {
        $stok = StokModel::with(['user', 'supplier', 'barang']);

        // Filter berdasarkan user, barang, dan supplier
        if ($request->user_id) {
            $stok->where('user_id', $request->user_id);
        }
        if ($request->barang_id) {
            $stok->where('barang_id', $request->barang_id);
        }
        if ($request->supplier_id) {
            $stok->where('supplier_id', $request->supplier_id);
        }

        return DataTables::of($stok)
            ->addIndexColumn()
            ->addColumn('aksi', function ($stok) {
                /*$btn = '<a href="' . url('/stok/' . $stok->stok_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/stok/' . $stok->stok_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/stok/' . $stok->stok_id) . '">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';*/
                $btn  = '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id .
                    '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Menampilkan halaman form tambah stok
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Stok',
            'list' => ['Home', 'Stok', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah stok baru'
        ];

        $users = UserModel::all();
        $suppliers = SupplierModel::all();
        $barang = BarangModel::all();
        $activeMenu = 'stok';

        return view('stok.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'users' => $users,
            'suppliers' => $suppliers,
            'barang' => $barang,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan data stok baru
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|integer',
            'barang_id' => 'required|integer',
            'user_id' => 'required|integer',
            'stok_tanggal' => 'required|date',
            'stok_jumlah' => 'required|integer'
        ]);

        StokModel::create([
            'supplier_id' => $request->supplier_id,
            'barang_id' => $request->barang_id,
            'user_id' => $request->user_id,
            'stok_tanggal' => $request->stok_tanggal,
            'stok_jumlah' => $request->stok_jumlah
        ]);

        return redirect('/stok')->with('success', 'Data stok berhasil disimpan');
    }

    // Menampilkan detail stok
    public function show(string $id)
    {
        $stok = StokModel::with(['user', 'supplier', 'barang'])->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Stok',
            'list' => ['Home', 'Stok', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail stok'
        ];

        $activeMenu = 'stok';

        return view('stok.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'stok' => $stok, 'activeMenu' => $activeMenu]);
    }

    // Menampilkan halaman form edit stok
    public function edit(string $id)
    {
        $stok = StokModel::find($id);
        $users = UserModel::all();
        $suppliers = SupplierModel::all();
        $barang = BarangModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit Stok',
            'list' => ['Home', 'Stok', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit stok'
        ];

        $activeMenu = 'stok';

        return view('stok.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'stok' => $stok,
            'users' => $users,
            'suppliers' => $suppliers,
            'barang' => $barang,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan perubahan data stok
    public function update(Request $request, string $id)
    {
        $request->validate([
            'supplier_id' => 'required|integer',
            'barang_id' => 'required|integer',
            'user_id' => 'required|integer',
            'stok_tanggal' => 'required|date',
            'stok_jumlah' => 'required|integer'
        ]);

        StokModel::find($id)->update([
            'supplier_id' => $request->supplier_id,
            'barang_id' => $request->barang_id,
            'user_id' => $request->user_id,
            'stok_tanggal' => $request->stok_tanggal,
            'stok_jumlah' => $request->stok_jumlah
        ]);

        return redirect('/stok')->with('success', 'Data stok berhasil diubah');
    }

    // Menghapus data stok
    public function destroy(string $id)
    {
        $stok = StokModel::find($id);

        if (!$stok) {
            return redirect('/stok')->with('error', 'Data stok tidak ditemukan');
        }

        try {
            StokModel::destroy($id);

            return redirect('/stok')->with('success', 'Data stok berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/stok')->with('error', 'Data stok gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
    public function create_ajax()
    {
        // Ambil data barang, user, dan supplier untuk dropdown
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();

        // Kirim data ke view create_ajax untuk stok
        return view('stok.create_ajax')
            ->with('barang', $barang)
            ->with('user', $user)
            ->with('supplier', $supplier);
    }

    public function store_ajax(Request $request)
    {
        // Cek apakah request berupa Ajax
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi
            $rules = [
                'barang_id' => 'required|integer|exists:m_barang,barang_id',
                'user_id' => 'required|integer|exists:m_user,user_id',
                'supplier_id' => 'required|integer|exists:m_supplier,supplier_id',
                'stok_tanggal' => 'required|date',
                'stok_jumlah' => 'required|integer|min:1'
            ];

            // Validasi data inputan
            $validator = Validator::make($request->all(), $rules);

            // Jika validasi gagal
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            // Simpan data stok ke database
            StokModel::create($request->all());

            // Kembalikan respon JSON jika berhasil
            return response()->json([
                'status' => true,
                'message' => 'Data stok berhasil disimpan'
            ]);
        }

        // Jika bukan request Ajax, redirect ke halaman lain
        return redirect('/');
    }
    public function edit_ajax(string $id)
    {
        $stok = StokModel::find($id);
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();

        return view('stok.edit_ajax', [
            'stok' => $stok,
            'barang' => $barang,
            'supplier' => $supplier,
            'user' => $user
        ]);
    }


    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'barang_id'   => 'required|integer',
                'supplier_id' => 'required|integer',
                'user_id'     => 'required|integer',
                'stok_jumlah' => 'required|integer|min:1',
                'stok_tanggal' => 'required|date'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $check = StokModel::find($id);
            if ($check) {
                $check->update($request->all());

                return response()->json([
                    'status'  => true,
                    'message' => 'Data stok berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data stok tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $stok = StokModel::find($id);
        return view('stok.confirm_ajax', ['stok' => $stok]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $stok = StokModel::find($id);

            if ($stok) {
                $stok->delete();

                return response()->json([
                    'status'  => true,
                    'message' => 'Data stok berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data stok tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function show_ajax(string $id)
    {
        $stok = StokModel::find($id);

        if ($stok) {
            return view('stok.show_ajax', ['stok' => $stok]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => 'Data stok tidak ditemukan'
            ]);
        }
    }
    public function import()
    {
        return view('stok.import');  // Menampilkan halaman import stok
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_stok' => ['required', 'mimes:xlsx', 'max:1024'] // Validasi file
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_stok'); // Mengambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // Load Excel reader
            $reader->setReadDataOnly(true); // Hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // Load file Excel
            $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet aktif

            $data = $sheet->toArray(null, false, true, true); // Ubah data sheet menjadi array

            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        // Convert Excel date to PHP DateTime object (if it's an Excel date format)
                        $stok_tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value['D'])->format('Y-m-d H:i:s');

                        $insert[] = [
                            'supplier_id' => $value['A'],
                            'barang_id' => $value['B'],
                            'user_id' => $value['C'],
                            'stok_tanggal' => $stok_tanggal, // Menggunakan tanggal dari Excel
                            'stok_jumlah' => $value['E'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    // Masukkan data ke database, abaikan jika data sudah ada
                    stokmodel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
    }
    public function export_excel()
    {
        // Ambil data stok yang akan diexport
        $stok = stokmodel::select('stok_id', 'supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
            ->with(['supplier', 'barang', 'user']) // Pastikan relasi sudah terdefinisi
            ->orderBy('stok_tanggal')
            ->get();

        // Load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif

        // Set header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal Stok');
        $sheet->setCellValue('C1', 'Supplier ID');
        $sheet->setCellValue('D1', 'Barang ID');
        $sheet->setCellValue('E1', 'User ID');
        $sheet->setCellValue('F1', 'Jumlah Stok');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true); // Bold header
        $no = 1;  // Nomor data dimulai dari 1
        $baris = 2; // Baris data dimulai dari baris ke 2

        foreach ($stok as $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->stok_tanggal); // Tanggal stok
            $sheet->setCellValue('C' . $baris, $value->supplier->supplier_nama);
            $sheet->setCellValue('D' . $baris, $value->barang->barang_nama);
            $sheet->setCellValue('E' . $baris, $value->user->nama);
            $sheet->setCellValue('F' . $baris, $value->stok_jumlah); // Jumlah stok
            $baris++;
            $no++;
        }

        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // Set auto size untuk kolom
        }

        $sheet->setTitle('Data Stok'); // Set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Stok ' . date('Y-m-d H:i:s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }
    public function export_pdf()
    {
        // Ambil data stok yang akan diexport
        $stok = stokmodel::select('stok_id', 'supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
            ->with(['supplier', 'barang', 'user']) // Pastikan relasi sudah terdefinisi
            ->orderBy('stok_tanggal')
            ->get();

        // Load view untuk PDF
        $pdf = Pdf::loadView('stok.export_pdf', ['stok' => $stok]);

        $pdf->setPaper('a4', 'portrait'); // Set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // Set true jika ada gambar dari URL
        $pdf->render();

        return $pdf->stream('Data Stok ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
