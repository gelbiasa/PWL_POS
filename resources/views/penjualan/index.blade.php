@extends('layouts.template')

@section('content')
  <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
          <a class="btn btn-sm btn-primary mt-1" href="{{ url('/penjualan/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Penjualan</a>
          <a class="btn btn-sm btn-warning mt-1" href="{{ url('/penjualan/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Penjualan</a>
          <button onclick="modalAction('{{ url('/penjualan/import') }}')" class="btn btn-sm btn-info mt-1">Import Penjualan</button> 
          <button onclick="modalAction('{{ url('/penjualan/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
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
                        <select class="form-control" id="user_id" name="user_id">
                            <option value="">- Semua User -</option>
                            @foreach($users as $user)
                                <option value="{{ $user->user_id }}">{{ $user->nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">User</small>
                    </div>
                </div>
            </div>
        </div>

        <div class= "table-responsive">
        <table class="table table-bordered table-striped table-hover table-sm" id="table_penjualan">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Penjualan</th>
                    <th>Tanggal Penjualan</th>
                    <th>Nama User</th>
                    <th>Pembeli</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
        </div>
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

    var dataPenjualan;
    $(document).ready(function() {
    dataPenjualan = $('#table_penjualan').DataTable({
        serverSide: true,
        ajax: {
            "url": "{{ url('penjualan/list') }}",
            "dataType": "json",
            "type": "POST",
            "data": function (d) {
                d.user_id = $('#user_id').val();
                d.barang_id = $('#barang_id').val(); // This can be removed if filtering by barang is not needed anymore
            }
        },
        columns: [
            { 
                data: "DT_RowIndex", 
                className: "text-center", 
                orderable: false, 
                searchable: false 
            },
            { 
                data: "penjualan_kode", 
                className: "", 
                orderable: true, 
                searchable: true 
            },
            { 
                data: "penjualan_tanggal", 
                className: "", 
                orderable: true, 
                searchable: true 
            },
            { 
                data: "user", // Now referring to user directly from the controller
                className: "", 
                orderable: true, 
                searchable: true 
            },
            { 
                data: "pembeli", 
                className: "", 
                orderable: true, 
                searchable: true 
            },
            { 
                data: "aksi", 
                className: "", 
                orderable: false, 
                searchable: false
            }
        ]
    });

    $('#user_id, #barang_id').on('change', function() {
        dataPenjualan.ajax.reload();
    });
});

  </script>
@endpush
