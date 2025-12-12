@extends('adminlte::page')

@section('title', 'Tambah Booking')

@section('content_header')
    <h1>Tambah Booking</h1>
@stop

@section('content')

<form action="{{ route('bookings.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label>Tamu</label>
        <select name="tamu_id" class="form-control" required>
            <option value="">-- Pilih Tamu --</option>
            @foreach ($tamu as $t)
                <option value="{{ $t->id }}">{{ $t->nama }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Kamar</label>
        <select name="room_id" class="form-control" required>
            <option value="">-- Pilih Kamar --</option>
            @foreach ($rooms as $r)
                <option value="{{ $r->id }}">{{ $r->nama_kamar }} (Rp {{ number_format($r->harga) }})</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Check In</label>
        <input type="date" name="check_in" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Check Out</label>
        <input type="date" name="check_out" class="form-control" required>
    </div>

    <button class="btn btn-primary">Simpan</button>
</form>

@stop
