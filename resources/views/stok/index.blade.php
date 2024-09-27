@extends('layouts.template')

@section('content')
  <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
          <a class="btn btn-sm btn-primary mt-1" href="{{ url('stok/create') }}">Tambah</a>
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
                    <div class="col-3">
                        <select class="form-control" id="barang_id" name="barang_id">
                            <option value="">- Semua Barang -</option>
                            @foreach($barang as $item)
                                <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Barang</small>
                    </div>
                    <div class="col-3">
                        <select class="form-control" id="supplier_id" name="supplier_id">
                            <option value="">- Semua Supplier -</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->supplier_id }}">{{ $supplier->supplier_nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Supplier</small>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-striped table-hover table-sm" id="table_stok">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>User</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
  </div>
@endsection

@push('css')
@endpush

@push('js')
  <script>
    $(document).ready(function() {
      var dataStok = $('#table_stok').DataTable({
          serverSide: true,
          ajax: {
              "url": "{{ url('stok/list') }}",
              "dataType": "json",
              "type": "POST",
              "data": function (d) {
                d.user_id = $('#user_id').val();
                d.barang_id = $('#barang_id').val();
                d.supplier_id = $('#supplier_id').val();
              }
          },
          columns: [
            { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
            { data: "stok_tanggal", orderable: true, searchable: true },
            { data: "supplier.supplier_nama", orderable: true, searchable: true },
            { data: "barang.barang_nama", orderable: true, searchable: true },
            { data: "stok_jumlah", orderable: true, searchable: true },
            { data: "user.nama", orderable: true, searchable: true },
            { data: "aksi", className: "", orderable: false, searchable: false }
          ]
      });

      $('#user_id, #barang_id, #supplier_id').on('change', function() {
          dataStok.ajax.reload();
      });
    });
  </script>
@endpush
