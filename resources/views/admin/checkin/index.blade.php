@extends('adminlte::page')

@section('title', 'Check-In Tamu')

@section('content_header')
    <h1>Daftar Check-In</h1>
@stop

@section('content')

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tamu</th>
            <th>Kamar</th>
            <th>Check-In</th>
            <th>Check-Out</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bookings as $b)
            <tr>
                <td>{{ $b->id }}</td>
                <td>{{ $b->tamu->nama }}</td>
                <td>{{ $b->room->nama_kamar }}</td>
                <td>{{ $b->check_in }}</td>
                <td>{{ $b->check_out }}</td>
                <td>Rp {{ number_format($b->total_bayar) }}</td>
                <td>
                    <form action="{{ route('admin.checkin.proses', $b->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-success btn-sm" onclick="return confirm('Proses Check-In?')">
                            Check-In
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach

        @if (count($bookings) == 0)
            <tr>
                <td colspan="7" class="text-center">Tidak ada tamu yang siap check-in</td>
            </tr>
        @endif
    </tbody>
</table>

@stop
