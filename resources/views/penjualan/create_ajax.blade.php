<form action="{{ url('/penjualan/ajax') }}" method="POST" id="form-tambah-penjualan">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Penjualan</label>
                    <input type="text" name="penjualan_kode" id="penjualan_kode" class="form-control" required>
                    <small id="error-penjualan_kode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Penjualan</label>
                    <input type="date" name="penjualan_tanggal" id="penjualan_tanggal" class="form-control" required>
                    <small id="error-penjualan_tanggal" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>User</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">- Pilih User -</option>
                        @foreach($user as $u)
                            <option value="{{ $u->user_id }}">{{ $u->nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-user_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Pembeli</label>
                    <input type="text" name="pembeli" id="pembeli" class="form-control" required>
                    <small id="error-pembeli" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Jumlah Barang</label>
                    <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control" min="1" required>
                    <small id="error-jumlah_barang" class="error-text form-text text-danger"></small>
                </div>

                <!-- Dynamic table for inputting item details -->
                <table class="table table-bordered" id="table-barang">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody id="barang-rows">
                        <!-- Dynamic rows added here -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-center">Total Harga</th>
                            <th>
                                <input type="text" name="total_harga" id="total_harga" class="form-control" readonly>
                            </th>
                        </tr>
                    </tfoot>
                </table>
                
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    function formatRupiah(angka) {
    let numberString = angka.toString();
    let sisa = numberString.length % 3;
    let rupiah = numberString.substr(0, sisa);
    let ribuan = numberString.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    return 'Rp ' + rupiah;
    }

    $(document).ready(function () {
    // Fungsi untuk menghitung total harga
    function updateTotal() {
    var total = 0;
    // Gunakan input tersembunyi yang menyimpan nilai angka asli untuk penjumlahan
    $('.harga-input-hidden').each(function() {
        var harga = $(this).val();
        if(harga) {
            total += parseInt(harga);
        }
    });
    // Tampilkan total harga dalam format rupiah
    $('#total_harga').val(formatRupiah(total));
    }

    // Tambah baris secara dinamis
    $('#jumlah_barang').on('change', function() {
        var jumlahBarang = $(this).val();
        var tableBody = $('#barang-rows');
        tableBody.empty(); // Hapus baris sebelumnya

        for (var i = 1; i <= jumlahBarang; i++) {
            var row = '<tr>' +
                '<td>' + i + '</td>' +
                '<td>' +
                    '<select name="barang_id[]" class="form-control barang-select" required>' +
                        '<option value="">- Pilih Barang -</option>' +
                        '@foreach($barang as $b)' +
                            '<option value="{{ $b->barang_id }}" data-harga="{{ $b->harga_jual }}">{{ $b->barang_nama }}</option>' +
                        '@endforeach' +
                    '</select>' +
                '</td>' +
                '<td>' +
                    '<input type="text" name="harga_satuan[]" class="form-control harga-satuan-input" readonly>' +
                '</td>' +
                '<td>' +
                    '<input type="number" name="jumlah[]" class="form-control jumlah-input" min="1" required>' +
                '</td>' +
                '<td>' +
                    '<input type="text" name="harga[]" class="form-control harga-input" readonly>' +
                    '<input type="hidden" name="harga_asli[]" class="harga-input-hidden">'
                '</td>' +
            '</tr>';
            tableBody.append(row);
        }
    });

    $(document).on('change', '.barang-select, .jumlah-input', function() {
    var row = $(this).closest('tr');
    var hargaSatuan = row.find('.barang-select option:selected').data('harga');
    var jumlah = row.find('.jumlah-input').val();

    if (hargaSatuan) {
        row.find('.harga-satuan-input').val(formatRupiah(hargaSatuan)); // Tampilkan harga satuan
    }

    if (hargaSatuan && jumlah) {
        var totalHarga = hargaSatuan * jumlah;

        // Simpan total harga asli (angka) ke input tersembunyi
        row.find('.harga-input-hidden').val(totalHarga); 

        // Tampilkan total harga dalam format rupiah
        row.find('.harga-input').val(formatRupiah(totalHarga));
    }

    updateTotal(); // Update total price
    });

        // Validation and form submission handling
        $("#form-tambah-penjualan").validate({
            rules: {
                penjualan_kode: { required: true },
                penjualan_tanggal: { required: true, date: true },
                user_id: { required: true, number: true },
                pembeli: { required: true },
                jumlah_barang: { required: true, number: true, min: 1 }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function (response) {
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
                            $.each(response.msgField, function (prefix, val) {
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
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>

<style>
    /* Apply light blue background to the table headers and total row */
    #table-barang th {
        background-color: lightblue; /* Light blue */
    }

    /* Optionally, you can add styles for better visibility of the total */
    #table-barang tfoot th,
    #table-barang tfoot td {
        background-color: lightblue; /* Light blue */
        font-weight: bold;
    }
</style>
