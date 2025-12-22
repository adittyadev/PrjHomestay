@extends('adminlte::page')

@section('title', 'Edit Tamu')

@section('content_header')
    <h1>Edit Data Tamu</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">

        <form action="{{ route('tamu.update', $tamu->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control"
                       value="{{ $tamu->nama }}" required>
            </div>

            <div class="form-group">
                <label>Email Tamu</label>
                <input type="email" name="email" class="form-control"
                       value="{{ $tamu->email }}" required>
            </div>

            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control"
                       value="{{ $tamu->no_hp }}" required>
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" required>{{ $tamu->alamat }}</textarea>
            </div>

            <button class="btn btn-primary">Update</button>

        </form>

    </div>
</div>
@stop
