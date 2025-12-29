@extends('adminlte::page')

@section('title', 'Data Booking')

@section('content_header')
    <h1>Data Booking</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

<a href="{{ route('bookings.create') }}" class="btn btn-primary mb-3">
    <i class="fas fa-plus"></i> Tambah Booking
</a>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Tamu</th>
                    <th>Kamar</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Total Bayar</th>
                    <th>Status Booking</th>
                    <th width="260">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $b->tamu->nama ?? '-' }}</td>
                    <td>{{ $b->room->nama_kamar ?? '-' }}</td>
                    <td>{{ $b->check_in }}</td>
                    <td>{{ $b->check_out }}</td>
                    <td>Rp {{ number_format($b->total_bayar, 0, ',', '.') }}</td>
<td>
    <span class="badge
        @if($b->status_booking == 'pending') badge-warning
        @elseif($b->status_booking == 'booked') badge-info
        @elseif($b->status_booking == 'checkin') badge-secondary
        @elseif($b->status_booking == 'checkout') badge-primary
        @elseif($b->status_booking == 'selesai') badge-success
        @elseif($b->status_booking == 'cancelled') badge-danger
        @endif">
        {{ strtoupper($b->status_booking) }}
    </span>
</td>
                    <td>

                        {{-- EDIT --}}
                        <a href="{{ route('bookings.edit', $b->id) }}"
                           class="btn btn-sm btn-warning mb-1">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        {{-- 🔥 TOMBOL KHUSUS JIKA STATUS = CHECKOUT --}}
                        @if($b->status_booking == 'checkout')
                            <form action="{{ route('bookings.updateStatus', $b->id) }}"
                                  method="POST" style="display:inline-block">
                                @csrf
                                <input type="hidden" name="status_booking" value="selesai">
                                <button class="btn btn-sm btn-success mb-1"
                                    onclick="return confirm('Tandai booking sebagai selesai?')">
                                    <i class="fas fa-check"></i> Selesai
                                </button>
                            </form>

                            <form action="{{ route('bookings.updateStatus', $b->id) }}"
                                  method="POST" style="display:inline-block">
                                @csrf
                                <input type="hidden" name="status_booking" value="cancelled">
                                <button class="btn btn-sm btn-danger mb-1"
                                    onclick="return confirm('Batalkan booking ini?')">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </form>
                        @endif

                        {{-- DELETE --}}
                        <form action="{{ route('bookings.destroy', $b->id) }}"
                              method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-secondary mb-1"
                                onclick="return confirm('Hapus booking ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">
                        <em>Data booking belum tersedia</em>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@stop
