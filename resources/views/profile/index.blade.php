@extends('layouts.template')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-outline card-primary"> 
                <div class="card-header">
                    <h3 class="card-title">{{ $page->title }}</h3> 
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ url('profile/update_profile') }}" enctype="multipart/form-data" id="profile-form">
                        @csrf
                        <div class="text-center">
                            <!-- Jika pengguna belum memiliki foto profil, tampilkan icon default -->
                            <img id="profile-pic" src="{{ Auth::user()->foto_profil ? asset('storage/' . Auth::user()->foto_profil) : asset('storage/gambar/user.png') }}" class="rounded-circle" width="200" alt="Profile Picture">
                            <div class="mt-2">
                                <label for="foto_profil" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Ganti Foto
                                </label>
                                <input type="file" id="foto_profil" name="foto_profil" class="d-none" onchange="previewImage(event)">
                            </div>
                            <small id="error-foto_profil" class="error-text text-danger"></small>
                        </div>

                        <div class="form-group mt-4">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" value="{{ Auth::user()->username }}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" value="******" disabled>
                        </div>

                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" value="{{ Auth::user()->nama }}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="level_nama">Level Pengguna</label>
                            <input type="text" class="form-control" id="level_nama" value="{{ Auth::user()->level->level_nama }}" disabled>
                        </div>

                        <div class="form-group text-right mt-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
    // SweetAlert2: Tampilkan notifikasi "Gambar berhasil diubah"
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
        });
    @endif

    // Preview gambar langsung setelah dipilih tanpa menunggu tombol Simpan
    function previewImage(event) {
        var fileInput = event.target;
        var file = fileInput.files[0];
        var error = document.getElementById('error-foto_profil');
        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
        var maxSize = 5 * 1024 * 1024; // 5 MB

        error.textContent = ''; // Hapus pesan error sebelumnya

        if (file) {
            // Validasi format file gambar
            if (!allowedExtensions.exec(file.name)) {
                error.textContent = 'Format gambar harus berupa jpeg, png, jpg, atau gif';
                fileInput.value = ''; // Bersihkan input jika format tidak sesuai
                return false;
            }

            // Validasi ukuran file gambar
            if (file.size > maxSize) {
                error.textContent = 'Gambar maksimal 5 MB';
                fileInput.value = ''; // Bersihkan input jika ukuran terlalu besar
                return false;
            }

            // Tampilkan preview gambar secara langsung
            var reader = new FileReader();
            reader.onload = function(e) {
                var output = document.getElementById('profile-pic');
                output.src = e.target.result; // Set gambar preview
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
