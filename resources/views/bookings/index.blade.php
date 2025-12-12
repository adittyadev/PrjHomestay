@extends('adminlte::page')

@section('title', 'Data Booking')

@section('content_header')
    <h1>Data Booking</h1>
@stop

@section('content')

<a href="{{ route('bookings.create') }}" class="btn btn-primary mb-3">Tambah Booking</a>

<table class="table table-bordered">
    <tr>
        <th>#</th>
        <th>Tamu</th>
        <th>Kamar</th>
        <th>Check In</th>
        <th>Check Out</th>
        <th>Total Bayar</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    @foreach ($bookings as $b)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $b->tamu->nama ?? '-' }}</td>
        <td>{{ $b->room->nama_kamar ?? '-' }}</td>
        <td>{{ $b->check_in }}</td>
        <td>{{ $b->check_out }}</td>
        <td>Rp {{ number_format($b->total_bayar) }}</td>
        <td>
            <span class="badge 
                @if($b->status_booking=='pending') bg-warning 
                @elseif($b->status_booking=='selesai') bg-success 
                @else bg-danger @endif">
                {{ ucfirst($b->status_booking) }}
            </span>
        </td>
        <td>
            <a href="{{ route('bookings.edit', $b->id) }}" class="btn btn-sm btn-warning">Edit</a>

            <form action="{{ route('bookings.destroy', $b->id) }}" method="POST" style="display:inline-block">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus booking ini?')">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach

</table>

@stop
