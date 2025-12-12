@extends('adminlte::page')

@section('title', 'Tambah Checkout')

@section('content_header')
    <h1>Tambah Checkout</h1>
@stop

@section('content')

<form action="{{ route('checkouts.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label>Booking</label>
        <select name="booking_id" class="form-control" required>
            <option value="">-- Pilih Booking --</option>
            @foreach ($bookings as $b)
                <option value="{{ $b->id }}">
                    Booking #{{ $b->id }} - {{ $b->guest->nama }} 
                    ({{ $b->room->nama_kamar }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Tanggal Checkout</label>
        <input type="datetime-local" name="tanggal_checkout" class="form-control" required>
    </div>

    <button class="btn btn-primary">Simpan</button>
</form>

@stop
