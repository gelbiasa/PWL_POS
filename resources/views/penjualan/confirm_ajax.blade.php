@empty($penjualan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" 
                aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data penjualan yang anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/penjualan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
<form action="{{ url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax') }}" method="POST" id="form-delete">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Data Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" 
                aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
                    Apakah Anda yakin ingin menghapus data penjualan berikut?
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr><th class="text-right col-3">Kode Penjualan :</th><td class="col-9">{{ $penjualan->penjualan_kode }}</td></tr>
                    <tr><th class="text-right col-3">Tanggal Penjualan :</th><td class="col-9">{{ $penjualan->penjualan_tanggal }}</td></tr>
                    <tr><th class="text-right col-3">User :</th><td class="col-9">{{ $penjualan->user->nama }}</td></tr>
                    <tr><th class="text-right col-3">Pembeli :</th><td class="col-9">{{ $penjualan->pembeli }}</td></tr>
                </table>

                <table class="table table-sm table-bordered table-striped mt-4">
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
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Ya, Hapus</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#form-delete").validate({
            rules: {},
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataPenjualan.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endempty
