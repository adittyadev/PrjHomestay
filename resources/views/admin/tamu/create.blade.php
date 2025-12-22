@extends('adminlte::page')

@section('title', 'Tambah Tamu')

@section('content_header')
    <h1>Tambah Tamu</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">

        <form action="{{ route('tamu.create') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Email Tamu</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Alamat (Opsional)</label>
                <textarea name="alamat" class="form-control"></textarea>
            </div>

            <button class="btn btn-primary">Simpan</button>
        </form>

    </div>
</div>
@stop
