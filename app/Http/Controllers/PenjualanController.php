<?php

namespace App\Http\Controllers;

use App\Models\PenjualanModel;
use App\Models\UserModel;
use App\Models\PenjualanDetailModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanController extends Controller
{
    // Menampilkan halaman awal penjualan
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Penjualan',
            'list' => ['Home', 'Penjualan']
        ];

        $page = (object) [
            'title' => 'Daftar penjualan yang tercatat dalam sistem'
        ];

        $users = UserModel::all();
        $barang = BarangModel::all();
        $activeMenu = 'penjualan'; // set menu yang sedang aktif

        return view('penjualan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'users' => $users,
            'barang' => $barang,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data penjualan dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        // Relasi dengan user
        $penjualan = PenjualanModel::with(['user']);

        // Filter berdasarkan user
        if ($request->user_id) {
            $penjualan->where('user_id', $request->user_id);
        }

        return DataTables::of($penjualan)
            ->addIndexColumn()
            ->addColumn('user', function ($penjualan) {
                return $penjualan->user->nama;
            })
            ->addColumn('aksi', function ($penjualan) {
                $btn  = '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Function to handle the AJAX-based creation form for penjualan
    public function create_ajax()
    {
        // Fetch data for barang and user dropdowns
        $barang = BarangModel::select('barang_id', 'barang_nama', 'harga_jual')->get();
        $user = UserModel::select('user_id', 'nama')->get();

        // Send data to the view for penjualan creation form
        return view('penjualan.create_ajax')
            ->with('barang', $barang)
            ->with('user', $user);
    }

    // Function to handle the AJAX-based storing of penjualan data
    public function store_ajax(Request $request)
    {
        // Check if the request is an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            // Validation rules for the penjualan and details
            $rules = [
                'penjualan_kode' => 'required|string|max:255|unique:t_penjualan,penjualan_kode',
                'penjualan_tanggal' => 'required|date',
                'user_id' => 'required|integer|exists:m_user,user_id',
                'pembeli' => 'required|string|max:255',
                'barang_id.*' => 'required|integer|exists:m_barang,barang_id',
                'jumlah.*' => 'required|integer|min:1'
            ];

            // Validate the input data
            $validator = Validator::make($request->all(), $rules);

            // If validation fails
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            // Create the penjualan record
            $penjualan = PenjualanModel::create([
                'penjualan_kode' => $request->penjualan_kode,
                'penjualan_tanggal' => $request->penjualan_tanggal,
                'user_id' => $request->user_id,
                'pembeli' => $request->pembeli,
            ]);

            // Store each penjualan detail (for each item sold)
            foreach ($request->barang_id as $index => $barangId) {
                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $barangId,
                    'jumlah' => $request->jumlah[$index],
                    'harga' => BarangModel::find($barangId)->harga_jual * $request->jumlah[$index],
                ]);
            }

            // Return JSON response if successful
            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil disimpan'
            ]);
        }

        // If it's not an AJAX request, redirect to another page
        return redirect('/');
    }

    // Function untuk menampilkan konfirmasi penghapusan data penjualan
    public function confirm_ajax(string $id)
    {
        // Temukan data penjualan berdasarkan ID
        $penjualan = PenjualanModel::with('penjualanDetail.barang', 'user')->find($id);

        // Kirim data penjualan ke view 'penjualan.confirm_ajax'
        return view('penjualan.confirm_ajax', ['penjualan' => $penjualan]);
    }

    // Function untuk menghapus data penjualan melalui ajax
    public function delete_ajax(Request $request, $id)
    {
        // Cek jika request datang dari ajax atau membutuhkan JSON response
        if ($request->ajax() || $request->wantsJson()) {
            // Cari data penjualan berdasarkan ID
            $penjualan = PenjualanModel::find($id);

            if ($penjualan) {
                // Hapus detail penjualan terkait terlebih dahulu
                $penjualan->penjualanDetail()->delete();

                // Hapus data penjualan utama
                $penjualan->delete();

                // Kembalikan response JSON berhasil
                return response()->json([
                    'status'  => true,
                    'message' => 'Data penjualan berhasil dihapus'
                ]);
            } else {
                // Kembalikan response JSON jika data tidak ditemukan
                return response()->json([
                    'status'  => false,
                    'message' => 'Data penjualan tidak ditemukan'
                ]);
            }
        }

        // Redirect ke halaman utama jika bukan AJAX request
        return redirect('/');
    }

    public function show_ajax(string $id)
    {
        // Fetch the Penjualan record along with its details and related tables
        $penjualan = PenjualanModel::with(['user', 'penjualanDetail.barang']) // Load related user and details with barang
            ->find($id);

        if ($penjualan) {
            // If found, return the 'penjualan.show_ajax' view and pass the penjualan data
            return view('penjualan.show_ajax', ['penjualan' => $penjualan]);
        } else {
            // If not found, return a JSON response indicating the error
            return response()->json([
                'status'  => false,
                'message' => 'Data penjualan tidak ditemukan'
            ]);
        }
    }
    // Function untuk menampilkan halaman import
    public function import()
    {
        return view('penjualan.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi file
            $rules = [
                'file_penjualan' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            // Mengambil file dari request
            $file = $request->file('file_penjualan');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();

            // Mengubah data sheet menjadi array
            $data = $sheet->toArray(null, false, true, true);

            $insertPenjualan = [];
            $insertPenjualanDetail = [];
            $penjualanKodeMap = [];

            // Variable untuk menyimpan nilai sebelumnya
            $lastUserId = null;
            $lastPembeli = null;
            $lastPenjualanKode = null;
            $lastPenjualanTanggal = null;

            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        // Jika kolom user_id kosong, gunakan nilai sebelumnya
                        $userId = $value['A'] ?? $lastUserId;
                        $pembeli = $value['B'] ?? $lastPembeli;
                        $penjualanKode = $value['C'] ?? $lastPenjualanKode;
                        $penjualanTanggal = !empty($value['D']) ?
                            \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value['D'])->format('Y-m-d H:i:s') :
                            $lastPenjualanTanggal;

                        // Update nilai sebelumnya jika kolom tidak kosong
                        if (!empty($value['A'])) $lastUserId = $value['A'];
                        if (!empty($value['B'])) $lastPembeli = $value['B'];
                        if (!empty($value['C'])) $lastPenjualanKode = $value['C'];
                        if (!empty($value['D'])) $lastPenjualanTanggal = $penjualanTanggal;

                        // Cek apakah penjualan_kode sudah ada di array penjualan yang akan dimasukkan
                        if (!isset($penjualanKodeMap[$penjualanKode])) {
                            // Masukkan data ke t_penjualan
                            $penjualan = PenjualanModel::create([
                                'user_id' => $userId,
                                'pembeli' => $pembeli,
                                'penjualan_kode' => $penjualanKode,
                                'penjualan_tanggal' => $penjualanTanggal,
                            ]);

                            // Simpan penjualan_id yang di-generate oleh database
                            $penjualanKodeMap[$penjualanKode] = $penjualan->penjualan_id;
                        }

                        // Masukkan data ke t_penjualan_detail dengan menghubungkan penjualan_id
                        $insertPenjualanDetail[] = [
                            'penjualan_id' => $penjualanKodeMap[$penjualanKode],
                            'barang_id' => $value['E'],
                            'harga' => $value['H'], // Gunakan kolom harga total dari Excel
                            'jumlah' => $value['G'],
                            'created_at' => now(),
                        ];
                    }
                }

                // Insert ke t_penjualan_detail secara batch
                if (count($insertPenjualanDetail) > 0) {
                    PenjualanDetailModel::insert($insertPenjualanDetail);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data penjualan berhasil diimport'
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
    // Ambil data penjualan yang akan diexport
    $penjualan = PenjualanModel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
        ->with(['user', 'penjualanDetail.barang']) // Gunakan relasi 'penjualanDetail' sesuai model
        ->orderBy('penjualan_tanggal')
        ->get();

    // Load library excel
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif

    // Set header untuk penjualan
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Tanggal Penjualan');
    $sheet->setCellValue('C1', 'Nama User');
    $sheet->setCellValue('D1', 'Nama Pembeli');
    $sheet->setCellValue('E1', 'Kode Penjualan');
    $sheet->setCellValue('F1', 'Nama Barang');
    $sheet->setCellValue('G1', 'Harga Satuan');
    $sheet->setCellValue('H1', 'Jumlah');
    $sheet->setCellValue('I1', 'Harga');

    $sheet->getStyle('A1:I1')->getFont()->setBold(true); // Bold header

    $no = 1;  // Nomor data dimulai dari 1
    $baris = 2; // Baris data dimulai dari baris ke 2

    // Loop untuk setiap penjualan
    foreach ($penjualan as $penj) {
        $startRow = $baris; // Menyimpan baris awal untuk merge

        // Loop untuk setiap detail penjualan
        foreach ($penj->penjualanDetail as $detail) {
            $sheet->setCellValue('F' . $baris, $detail->barang->barang_nama); // Nama barang
            $sheet->setCellValue('G' . $baris, $detail->barang->harga_jual); // Harga Satuan
            $sheet->setCellValue('H' . $baris, $detail->jumlah); // Jumlah barang
            $sheet->setCellValue('I' . $baris, $detail->harga); // Harga total per barang

            $baris++;
        }

        // Merge cell untuk kolom A hingga E jika ada lebih dari satu barang
        if ($baris - 1 > $startRow) {
            // Merge cells
            $sheet->mergeCells('A' . $startRow . ':A' . ($baris - 1)); // Nomor
            $sheet->mergeCells('B' . $startRow . ':B' . ($baris - 1)); // Tanggal Penjualan
            $sheet->mergeCells('C' . $startRow . ':C' . ($baris - 1)); // User ID
            $sheet->mergeCells('D' . $startRow . ':D' . ($baris - 1)); // Nama Pembeli
            $sheet->mergeCells('E' . $startRow . ':E' . ($baris - 1)); // Kode Penjualan

            // Set alignment tengah dan tengah untuk cell yang dimerge
            $sheet->getStyle('A' . $startRow . ':E' . ($baris - 1))
                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        }

        // Mengisi data penjualan di baris pertama dari hasil merge
        $sheet->setCellValue('A' . $startRow, $no); // Nomor
        $sheet->setCellValue('B' . $startRow, $penj->penjualan_tanggal); // Tanggal penjualan
        $sheet->setCellValue('C' . $startRow, $penj->user->nama); // Nama user
        $sheet->setCellValue('D' . $startRow, $penj->pembeli); // Nama pembeli
        $sheet->setCellValue('E' . $startRow, $penj->penjualan_kode); // Kode penjualan

        $no++;
    }

    // Set auto size untuk kolom
    foreach (range('A', 'I') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Set title sheet
    $sheet->setTitle('Data Penjualan');

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $filename = 'Data Penjualan ' . date('Y-m-d H:i:s') . '.xlsx';

    // Pengaturan header untuk download file excel
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
        // Ambil data penjualan yang akan diexport
        $penjualan = PenjualanModel::select('penjualan_id', 'penjualan_kode', 'penjualan_tanggal', 'user_id', 'pembeli')
            ->with(['penjualanDetail.barang', 'user']) // Pastikan relasi sudah terdefinisi
            ->orderBy('penjualan_tanggal')
            ->get();

        // Load view untuk PDF
        $pdf = Pdf::loadView('penjualan.export_pdf', ['penjualan' => $penjualan]);

        $pdf->setPaper('a4', 'portrait'); // Set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // Set true jika ada gambar dari URL
        $pdf->render();

        return $pdf->stream('Data Penjualan ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
