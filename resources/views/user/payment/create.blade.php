@extends('adminlte::page')

@section('content')
<div class="container">
    <h4 class="mb-4">Pembayaran</h4>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Kamar:</strong> {{ $booking->room->nama_kamar }}</p>
            <p><strong>Check In:</strong> {{ $booking->check_in }}</p>
            <p><strong>Check Out:</strong> {{ $booking->check_out }}</p>
            <p><strong>Total Bayar:</strong> 
                Rp {{ number_format($booking->total_bayar) }}
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('payment.store',$booking->id) }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Metode Pembayaran</label>
            <select name="metode" class="form-control" required>
                <option value="">-- Pilih Metode --</option>
                <option value="transfer">Transfer</option>
                <option value="dana">DANA</option>
                <option value="ovo">OVO</option>
                <option value="gopay">GOPAY</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Upload Bukti Pembayaran</label>
            <input type="file" name="bukti_transfer" class="form-control" required>
        </div>

        <button class="btn btn-success">
            Kirim Bukti Pembayaran
        </button>
    </form>
</div>
@endsection
