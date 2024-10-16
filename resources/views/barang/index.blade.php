@extends('layouts.template')

@section('content')
  <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
          <a class="btn btn-sm btn-primary mt-1" h href="{{ url('/barang/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Barang</a>
          <button onclick="modalAction('{{ url('/barang/import') }}')" class="btn btn-sm btn-info mt-1">Import Barang</button> 
          <button onclick="modalAction('{{ url('/barang/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
          
        </div>
      </div>
      <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="kategori_id" name="kategori_id" required>
                            <option value="">- Semua -</option>
                            @foreach($kategori as $item)
                                <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Kategori Barang</small>
                    </div>
                </div>
            </div>
        </div>

        <div class= "table-responsive">
        <table class="table table-bordered table-striped table-hover table-sm" id="table_barang">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Kategori Barang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table> </div>
    </div>
  </div>
  <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" 
   data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
  <script>
    function modalAction(url = ''){ 
    $('#myModal').load(url,function(){ 
        $('#myModal').modal('show'); 
    }); 
} 
var dataBarang;
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
    $(document).ready(function() {
      dataBarang = $('#table_barang').DataTable({
          serverSide: true,
          ajax: {
              "url": "{{ url('barang/list') }}",
              "dataType": "json",
              "type": "POST",
              "data": function (d) {
                d.kategori_id = $('#kategori_id').val();
              }
          },
          columns: [
            {
              data: "DT_RowIndex",             
              className: "text-center",
              orderable: false,
              searchable: false
            },{
              data: "barang_kode",                
              className: "",
              orderable: true,
              searchable: true
            },{
              data: "barang_nama",                
              className: "",
              orderable: true,
              searchable: true
            },{
              data: "harga_beli",                
              className: "",
              orderable: true,
              searchable: true,
              render: function(data, type, row) {
                        return formatRupiah(data); // Format harga_beli as Rupiah
              }
            },{
              data: "harga_jual",                
              className: "",
              orderable: true,
              searchable: true,
              render: function(data, type, row) {
                        return formatRupiah(data); // Format harga_beli as Rupiah
              }
            },{
              data: "kategori.kategori_nama",                
              className: "",
              orderable: false,
              searchable: false,
            },{
              data: "aksi",
              className: "",
              orderable: false,
              searchable: false
            }
          ]
        });
        
        $('#kategori_id').on('change', function() {
            dataBarang.ajax.reload();
        });
    });
  </script>
@endpush
