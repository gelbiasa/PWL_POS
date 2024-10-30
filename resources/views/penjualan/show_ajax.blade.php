@empty($penjualan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data penjualan yang anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/penjualan/') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Informasi Horizontal (Kode Penjualan, Tanggal Penjualan, User, Pembeli) -->
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info-circle"></i> Informasi !!!</h5>
                    Berikut adalah detail data penjualan:
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID Penjualan :</th>
                        <td class="col-9">{{ $penjualan->penjualan_id }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kode Penjualan :</th>
                        <td class="col-9">{{ $penjualan->penjualan_kode }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Penjualan :</th>
                        <td class="col-9">{{ $penjualan->penjualan_tanggal }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">User :</th>
                        <td class="col-9">{{ $penjualan->user->nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Pembeli :</th>
                        <td class="col-9">{{ $penjualan->pembeli }}</td>
                    </tr>
                </table>

                <!-- Daftar Barang (Vertical Display for Barang, Jumlah, Harga) -->
                <div class="alert alert-secondary">
                    <h5><i class="icon fas fa-list"></i> Daftar Barang yang Dibeli Pelanggan</h5>
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <thead>
                        <tr class="text-center" style="background-color: lightblue;">
                            <th>No</th>
                            <th>Barang</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalHarga = 0; @endphp
                        @foreach($penjualan->penjualanDetail as $key => $detail)
                        @php
                            $hargaSatuan = $detail->barang->harga_jual;
                            $jumlah = $detail->jumlah;
                            $totalItemHarga = $hargaSatuan * $jumlah;
                            $totalHarga += $totalItemHarga;
                        @endphp
                        <tr class="text-center">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $detail->barang->barang_nama }}</td>
                            <td>{{ 'Rp ' . number_format($hargaSatuan, 0, ',', '.') }}</td>
                            <td>{{ $jumlah }}</td>
                            <td>{{ 'Rp ' . number_format($totalItemHarga, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background-color: lightblue;">
                            <th colspan="4" class="text-center">Total Harga</th>
                            <th class="text-center">{{ 'Rp ' . number_format($totalHarga, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>                    
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button>
            </div>
        </div>
    </div>
@endempty