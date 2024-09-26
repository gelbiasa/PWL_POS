@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <div class="card-body">
        @empty($supplier)
            <div class="alert alert-danger">Data supplier tidak ditemukan.</div>
        @else
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th>ID</th>
                    <td>{{ $supplier->supplier_id }}</td>
                </tr>
                <tr>
                    <th>Kode Supplier</th>
                    <td>{{ $supplier->supplier_kode }}</td>
                </tr>
                <tr>
                    <th>Nama Supplier</th>
                    <td>{{ $supplier->supplier_nama }}</td>
                </tr>
                <tr>
                    <th>Alamat Supplier</th>
                    <td>{{ $supplier->supplier_alamat }}</td>
                </tr>
            </table>
            <a href="{{ url('supplier') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        @endempty
    </div>
</div>
@endsection
