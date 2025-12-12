@extends('adminlte::page')

@section('title', 'Tambah Kamar')

@section('content_header')
    <h1>Tambah Kamar</h1>
@stop

@section('content')

<form action="{{ route('rooms.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
        <label>Nama Kamar</label>
        <input type="text" name="nama_kamar" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Kapasitas (orang)</label>
        <input type="number" name="kapasitas" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Harga per Malam</label>
        <input type="number" name="harga" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="available">Available</option>
            <option value="booked">Booked</option>
        </select>
    </div>

    <div class="form-group">
        <label>Foto Kamar</label>
        <input type="file" name="foto" class="form-control">
    </div>

    <button class="btn btn-primary">Simpan</button>
</form>

@stop
