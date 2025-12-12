@extends('adminlte::page')

@section('title', 'Dashboard Admin')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0 text-dark">Dashboard Admin</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <div class="row">

            <!-- Total Kamar -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $rooms }}</h3>
                        <p>Total Kamar</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-bed"></i>
                    </div>
                    <a href="{{ route('rooms.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Total Tamu -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $tamu }}</h3>
                        <p>Total Tamu</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('tamu.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Total Booking -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $bookings }}</h3>
                        <p>Total Booking</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <a href="{{ route('bookings.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>


            <!-- Pembayaran Pending -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $pending }}</h3>
                        <p>Pembayaran Pending</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill"></i>
                    </div>
                    <a href="{{ route('payments.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

        </div>

        <!-- Grafik -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Grafik Booking Mingguan</h3>
            </div>
            <div class="card-body">
                <canvas id="chartBooking"></canvas>
            </div>
        </div>

    </div>
</section>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('chartBooking').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($grafik['tanggal']),
            datasets: [{
                label: 'Booking',
                data: @json($grafik['jumlah']),
                borderWidth: 2,
                fill: false
            }]
        }
    });
</script>

@endsection
