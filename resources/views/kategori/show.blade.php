@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <div class="card-body">
        @empty($kategori)
            <div class="alert alert-danger">Data kategori tidak ditemukan.</div>
        @else
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th>ID</th>
                    <td>{{ $kategori->kategori_id }}</td>
                </tr>
                <tr>
                    <th>Kode Kategori</th>
                    <td>{{ $kategori->kategori_kode }}</td>
                </tr>
                <tr>
                    <th>Nama Kategori</th>
                    <td>{{ $kategori->kategori_nama }}</td>
                </tr>
            </table>
            <a href="{{ url('kategori') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        @endempty
    </div>
</div>
@endsection
