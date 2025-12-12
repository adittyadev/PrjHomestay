@extends('adminlte::page')

@section('title', 'Daftar Checkout')

@section('content_header')
    <h1>Daftar Booking Siap Checkout</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID Booking</th>
            <th>Nama Tamu</th>
            <th>Kamar</th>
            <th>Check-In</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bookings as $b)
        <tr>
            <td>#{{ $b->id }}</td>
            <td>{{ $b->tamu->nama }}</td>
            <td>{{ $b->room->nama_kamar }}</td>
            <td>{{ $b->check_in }}</td>
            <td>
                <form action="{{ route('admin.checkout.proses', $b->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-danger btn-sm"
                        onclick="return confirm('Konfirmasi checkout tamu ini?')">
                        Checkout
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@stop
