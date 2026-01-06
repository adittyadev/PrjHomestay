@extends('adminlte::page')

@section('title', 'Detail Booking')

@section('content_header')
    <h1>Detail Booking</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-8">
        {{-- Card Info Booking --}}
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> Informasi Booking</h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Kode Booking</th>
                        <td>: <strong>{{ $booking->kode_booking ?? '-' }}</strong></td>
                    </tr>
                    <tr>
                        <th>Tanggal Booking</th>
                        <td>: {{ \Carbon\Carbon::parse($booking->created_at)->format('d F Y, H:i') }} WIB</td>
                    </tr>
                    <tr>
                        <th>Status Booking</th>
                        <td>: 
                            <span class="badge badge-lg
                                @if($booking->status_booking == 'pending') badge-warning
                                @elseif($booking->status_booking == 'booked') badge-info
                                @elseif($booking->status_booking == 'checkin') badge-secondary
                                @elseif($booking->status_booking == 'checkout') badge-primary
                                @elseif($booking->status_booking == 'selesai') badge-success
                                @elseif($booking->status_booking == 'cancelled') badge-danger
                                @endif">
                                <i class="fas fa-circle"></i> {{ strtoupper($booking->status_booking) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Card Info Tamu --}}
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user"></i> Informasi Tamu</h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Nama Tamu</th>
                        <td>: {{ $booking->tamu->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>: {{ $booking->tamu->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>No. Telepon</th>
                        <td>: {{ $booking->tamu->no_hp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>: {{ $booking->tamu->alamat ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Card Info Kamar --}}
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-bed"></i> Informasi Kamar</h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Nama Kamar</th>
                        <td>: {{ $booking->room->nama_kamar ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Harga per Malam</th>
                        <td>: Rp {{ number_format($booking->room->harga ?? 0, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Card Jadwal Menginap --}}
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Jadwal Menginap</h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Check In</th>
                        <td>: {{ \Carbon\Carbon::parse($booking->check_in)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Check Out</th>
                        <td>: {{ \Carbon\Carbon::parse($booking->check_out)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Durasi Menginap</th>
                        <td>: 
                            @php
                                $checkIn = \Carbon\Carbon::parse($booking->check_in);
                                $checkOut = \Carbon\Carbon::parse($booking->check_out);
                                $durasi = $checkIn->diffInDays($checkOut);
                            @endphp
                            <strong>{{ $durasi }} Malam</strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Card Catatan --}}
        @if($booking->catatan)
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-sticky-note"></i> Catatan</h3>
            </div>
            <div class="card-body">
                <p>{{ $booking->catatan }}</p>
            </div>
        </div>
        @endif

    </div>

    <div class="col-md-4">
        {{-- Card Ringkasan Pembayaran --}}
        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-money-bill-wave"></i> Ringkasan Pembayaran</h3>
            </div>
            <div class="card-body">
                @php
                    $checkIn = \Carbon\Carbon::parse($booking->check_in);
                    $checkOut = \Carbon\Carbon::parse($booking->check_out);
                    $durasi = $checkIn->diffInDays($checkOut);
                    $hargaPerMalam = $booking->room->harga ?? 0;
                    $subtotal = $durasi * $hargaPerMalam;
                @endphp

                <table class="table table-sm">
                    <tr>
                        <td>Harga per Malam</td>
                        <td class="text-right">Rp {{ number_format($hargaPerMalam, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Durasi</td>
                        <td class="text-right">{{ $durasi }} Malam</td>
                    </tr>
                    <tr>
                        <td>Subtotal</td>
                        <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="border-top">
                        <th>Total Bayar</th>
                        <th class="text-right text-danger">
                            <h4>Rp {{ number_format($booking->total_bayar, 0, ',', '.') }}</h4>
                        </th>
                    </tr>
                </table>

                @if($booking->status_pembayaran)
                <div class="mt-3">
                    <small class="text-muted">Status Pembayaran:</small><br>
                    <span class="badge 
                        @if($booking->status_pembayaran == 'lunas') badge-success
                        @elseif($booking->status_pembayaran == 'dp') badge-warning
                        @else badge-secondary
                        @endif">
                        {{ strtoupper($booking->status_pembayaran) }}
                    </span>
                </div>
                @endif
            </div>
        </div>

        {{-- Card Aksi --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-cog"></i> Aksi</h3>
            </div>
            <div class="card-body">
                
                {{-- Update Status untuk Checkout --}}
                @if($booking->status_booking == 'checkout')
                    <form action="{{ route('bookings.updateStatus', $booking->id) }}" 
                          method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="status_booking" value="selesai">
                        <button type="submit" class="btn btn-success btn-block"
                                onclick="return confirm('Tandai booking sebagai selesai?')">
                            <i class="fas fa-check"></i> Tandai Selesai
                        </button>
                    </form>

                    <form action="{{ route('bookings.updateStatus', $booking->id) }}" 
                          method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="status_booking" value="cancelled">
                        <button type="submit" class="btn btn-danger btn-block"
                                onclick="return confirm('Batalkan booking ini?')">
                            <i class="fas fa-times"></i> Batalkan Booking
                        </button>
                    </form>
                @endif

                {{-- Kembali --}}
                <a href="{{ route('bookings.index') }}" class="btn btn-secondary btn-block">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>

            </div>
        </div>

        {{-- Card Riwayat Status --}}
        @if(isset($booking->riwayat_status) && count($booking->riwayat_status) > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-history"></i> Riwayat Status</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($booking->riwayat_status as $riwayat)
                    <li class="list-group-item">
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($riwayat->created_at)->format('d/m/Y H:i') }}
                        </small><br>
                        <span class="badge badge-secondary">{{ $riwayat->status }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

    </div>
</div>

@stop

@section('css')
<style>
    .card-header {
        font-weight: bold;
    }
    .table th {
        font-weight: 600;
    }
    .badge-lg {
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }
</style>
@stop