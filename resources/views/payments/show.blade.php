@extends('adminlte::page')

@section('title', 'Detail Pembayaran')

@section('content_header')
    <h1>
        <i class="fas fa-money-check-alt"></i> Detail Pembayaran
    </h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-8">
        {{-- Card Info Pembayaran --}}
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> Informasi Pembayaran</h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">ID Pembayaran</th>
                        <td>: <strong>#{{ $payment->id }}</strong></td>
                    </tr>
                    <tr>
                        <th>ID Booking</th>
                        <td>: <strong>#{{ $payment->booking_id }}</strong></td>
                    </tr>
                    <tr>
                        <th>Tanggal Pembayaran</th>
                        <td>: {{ \Carbon\Carbon::parse($payment->created_at)->format('d F Y, H:i') }} WIB</td>
                    </tr>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <td>: 
                            <span class="badge badge-secondary badge-lg">
                                {{ strtoupper($payment->metode ?? 'Transfer Bank') }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Jumlah Pembayaran</th>
                        <td>: 
                            <h4 class="text-success">
                                <strong>Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</strong>
                            </h4>
                        </td>
                    </tr>
                    <tr>
                        <th>Status Pembayaran</th>
                        <td>: 
                            <span class="badge badge-lg
                                @if($payment->status == 'valid') badge-success
                                @elseif($payment->status == 'invalid') badge-danger
                                @else badge-warning
                                @endif">
                                <i class="fas fa-circle"></i> {{ strtoupper($payment->status) }}
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
                        <td>: {{ $payment->booking->tamu->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>: {{ $payment->booking->tamu->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>No. Telepon</th>
                        <td>: {{ $payment->booking->tamu->no_hp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>: {{ $payment->booking->tamu->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>NIK</th>
                        <td>: {{ $payment->booking->tamu->nik ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Card Info Booking --}}
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-calendar-check"></i> Informasi Booking</h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Kamar</th>
                        <td>: {{ $payment->booking->room->nama_kamar ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Nomor Kamar</th>
                        <td>: {{ $payment->booking->room->nomor_kamar ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tipe Kamar</th>
                        <td>: {{ $payment->booking->room->tipe_kamar ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Check-In</th>
                        <td>: {{ \Carbon\Carbon::parse($payment->booking->check_in)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Check-Out</th>
                        <td>: {{ \Carbon\Carbon::parse($payment->booking->check_out)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Durasi Menginap</th>
                        <td>: 
                            @php
                                $checkIn = \Carbon\Carbon::parse($payment->booking->check_in);
                                $checkOut = \Carbon\Carbon::parse($payment->booking->check_out);
                                $durasi = $checkIn->diffInDays($checkOut);
                            @endphp
                            <strong>{{ $durasi }} Malam</strong>
                        </td>
                    </tr>
                    <tr>
                        <th>Total Booking</th>
                        <td>: Rp {{ number_format($payment->booking->total_bayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Status Booking</th>
                        <td>: 
                            <span class="badge
                                @if($payment->booking->status_booking == 'pending') badge-warning
                                @elseif($payment->booking->status_booking == 'booked') badge-info
                                @elseif($payment->booking->status_booking == 'checkin') badge-secondary
                                @elseif($payment->booking->status_booking == 'checkout') badge-primary
                                @elseif($payment->booking->status_booking == 'selesai') badge-success
                                @elseif($payment->booking->status_booking == 'cancelled') badge-danger
                                @endif">
                                {{ strtoupper($payment->booking->status_booking) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Card Catatan --}}
        @if(isset($payment->keterangan) && $payment->keterangan)
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-sticky-note"></i> Catatan</h3>
            </div>
            <div class="card-body">
                <p>{{ $payment->keterangan }}</p>
            </div>
        </div>
        @endif

    </div>

    <div class="col-md-4">
        {{-- Card Bukti Transfer --}}
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-image"></i> Bukti Transfer</h3>
            </div>
            <div class="card-body text-center">
                @if($payment->bukti_transfer)
                    @php
                        // Cek berbagai kemungkinan path
                        $imagePath = null;
                        $possiblePaths = [
                            'storage/payments/' . $payment->bukti_transfer,
                            'storage/' . $payment->bukti_transfer,
                            $payment->bukti_transfer
                        ];
                        
                        foreach ($possiblePaths as $path) {
                            if (file_exists(public_path($path))) {
                                $imagePath = asset($path);
                                break;
                            }
                        }
                    @endphp
                    
                    @if($imagePath)
                        <a href="{{ $imagePath }}" target="_blank">
                            <img src="{{ $imagePath }}" 
                                 class="img-fluid rounded"
                                 style="max-height: 400px; cursor: pointer;"
                                 alt="Bukti Transfer"
                                 onerror="this.parentElement.innerHTML='<div class=\'py-5\'><i class=\'fas fa-exclamation-triangle fa-5x text-warning mb-3\'></i><p class=\'text-muted\'>Gambar tidak dapat dimuat</p></div>'">
                        </a>
                        <p class="text-muted mt-2">
                            <small>Klik gambar untuk melihat ukuran penuh</small>
                        </p>
                    @else
                        <div class="py-5">
                            <i class="fas fa-exclamation-triangle fa-5x text-warning mb-3"></i>
                            <p class="text-muted">File: {{ $payment->bukti_transfer }}</p>
                            <p class="text-muted">Gambar tidak ditemukan di server</p>
                        </div>
                    @endif
                @else
                    <div class="py-5">
                        <i class="fas fa-file-image fa-5x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada bukti transfer</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Card Aksi --}}
{{-- Card Aksi --}}
<div class="card">
    <div class="card-header bg-gradient-dark">
        <h3 class="card-title">
            <i class="fas fa-cog"></i> Aksi
        </h3>
    </div>
    <div class="card-body">

        {{-- Lihat Booking --}}
        <a href="{{ route('bookings.show', $payment->booking_id) }}" 
           class="btn btn-info btn-block mb-2">
            <i class="fas fa-calendar-alt"></i> Lihat Detail Booking
        </a>

        {{-- Kembali --}}
        <a href="{{ route('payments.index') }}" 
           class="btn btn-secondary btn-block">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>

    </div>
</div>


        {{-- Card Timeline --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-history"></i> Timeline</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> 
                            {{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y H:i') }}
                        </small><br>
                        <strong>Pembayaran Dibuat</strong>
                    </li>
                    @if($payment->updated_at != $payment->created_at)
                    <li class="list-group-item">
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> 
                            {{ \Carbon\Carbon::parse($payment->updated_at)->format('d/m/Y H:i') }}
                        </small><br>
                        <strong>Status Diperbarui</strong>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

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
    .img-fluid {
        border: 3px solid #dee2e6;
        transition: transform 0.3s ease;
    }
    .img-fluid:hover {
        transform: scale(1.05);
    }
</style>
@stop