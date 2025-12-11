@extends('adminlte::page')

@section('title', 'Edit Kamar')

@section('content_header')
    <h1>Edit Kamar</h1>
@stop

@section('content')

<form action="{{ route('rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Nama Kamar</label>
        <input type="text" name="nama_kamar" class="form-control" value="{{ $room->nama_kamar }}" required>
    </div>

    <div class="form-group">
        <label>Kapasitas</label>
        <input type="number" name="kapasitas" class="form-control" value="{{ $room->kapasitas }}" required>
    </div>

    <div class="form-group">
        <label>Harga</label>
        <input type="number" name="harga" class="form-control" value="{{ $room->harga }}" required>
    </div>

    <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="available" {{ $room->status == 'available' ? 'selected' : '' }}>Available</option>
            <option value="booked" {{ $room->status == 'booked' ? 'selected' : '' }}>Booked</option>
        </select>
    </div>

    <div class="form-group">
        <label>Foto Kamar</label><br>
        @if($room->foto)
            <img src="{{ asset('uploads/rooms/'.$room->foto) }}" width="120"><br><br>
        @endif
        <input type="file" name="foto" class="form-control">
    </div>

    <button class="btn btn-primary">Update</button>
</form>

@stop
