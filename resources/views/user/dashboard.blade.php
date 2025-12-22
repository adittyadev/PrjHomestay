@extends('adminlte::page')

@section('content')
<div class="container">
    <h4 class="mb-4">Dashboard User</h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($bookings->count())
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kamar</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Total</th>
                <th>Status Booking</th>
                <th>Status Pembayaran</th>
                <th>Aksi</th>  {{-- ⬅️ KOLOM AKSI --}}
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
            <tr>
                <td>{{ $booking->room->nama_kamar }}</td>
                <td>{{ $booking->check_in }}</td>
                <td>{{ $booking->check_out }}</td>
                <td>Rp {{ number_format($booking->total_bayar) }}</td>
                <td>
                    <span class="badge bg-warning">
                        {{ ucfirst($booking->status_booking) }}
                    </span>
                </td>
                <td>
                    @if($booking->payment)
                        <span class="badge bg-info">
                            {{ ucfirst($booking->payment->status) }}
                        </span>
                    @else
                        <span class="badge bg-secondary">
                            Belum Bayar
                        </span>
                    @endif
                </td>
                <td>
                    {{-- ⬇️ DI SINI TOMBOL BAYAR --}}
                    @if(!$booking->payment)
                        <a href="{{ route('payment.create',$booking->id) }}"
                        class="btn btn-sm btn-primary">
                        Bayar
                        </a>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p>Belum ada booking.</p>
    @endif
</div>
@endsection
