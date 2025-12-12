@extends('adminlte::page')

@section('title', 'Tambah Pembayaran')

@section('content_header')
<h1>Tambah Pembayaran</h1>
@stop

@section('content')

<form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="form-group">
    <label>Booking</label>
    <select name="booking_id" id="booking_id" class="form-control" required>
        <option value="">-- Pilih Booking --</option>
        @foreach ($bookings as $b)
            <option value="{{ $b->id }}" data-total="{{ $b->total_bayar }}">
                #{{ $b->id }} - {{ $b->tamu->nama }} | Rp {{ number_format($b->total_bayar) }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Jumlah (Otomatis)</label>
    <input type="number" name="jumlah" id="jumlah" class="form-control" readonly>
</div>

<div class="form-group">
    <label>Metode Pembayaran</label>
    <select name="metode" class="form-control">
        <option value="transfer">Transfer</option>
        <option value="dana">DANA</option>
        <option value="ovo">OVO</option>
        <option value="gopay">Gopay</option>
    </select>
</div>

<div class="form-group">
    <label>Bukti Transfer</label>
    <input type="file" name="bukti_transfer" class="form-control">
</div>

<button class="btn btn-primary">Simpan</button>

</form>

<script>
document.getElementById('booking_id').addEventListener('change', function() {
    let total = this.options[this.selectedIndex].getAttribute('data-total');
    document.getElementById('jumlah').value = total;
});
</script>

@stop
