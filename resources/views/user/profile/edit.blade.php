@extends('adminlte::page')

@section('content')
<div class="container">
    <h4 class="mb-4">Profil Saya</h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('user.profile.update') }}">
        @csrf

        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama"
                   value="{{ old('nama',$tamu->nama) }}"
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email Kontak</label>
            <input type="email" name="email"
                   value="{{ old('email',$tamu->email) }}"
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Nomor HP</label>
            <input type="text" name="no_hp"
                   value="{{ old('no_hp',$tamu->no_hp) }}"
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat"
                      class="form-control">{{ old('alamat',$tamu->alamat) }}</textarea>
        </div>

        <button class="btn btn-primary">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection
