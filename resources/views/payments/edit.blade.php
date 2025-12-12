@extends('adminlte::page')

@section('title', 'Edit Pembayaran')

@section('content_header')
<h1>Edit Pembayaran</h1>
@stop

@section('content')

<form action="{{ route('payments.update', $payment->id) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

<div class="form-group">
    <label>Booking</label>
    <select name="booking_id" class="form-control">
        @foreach ($bookings as $b)
            <option value="{{ $b->id }}" {{ $b->id == $payment->booking_id ? 'selected' : '' }}>
                #{{ $b->id }} - {{ $b->tamu->nama }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Metode</label>
    <select name="metode" class="form-control">
        @foreach(['transfer','dana','ovo','gopay'] as $m)
            <option value="{{ $m }}" {{ $payment->metode == $m ? 'selected' : '' }}>
                {{ ucfirst($m) }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Jumlah</label>
    <input type="number" name="jumlah" class="form-control" value="{{ $payment->jumlah }}">
</div>

<div class="form-group">
    <label>Status Pembayaran</label>
    <select name="status" class="form-control">
        @foreach(['pending','valid','invalid'] as $s)
            <option value="{{ $s }}" {{ $payment->status == $s ? 'selected' : '' }}>
                {{ ucfirst($s) }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Bukti Transfer</label><br>
    @if ($payment->bukti_transfer)
        <img src="{{ asset('storage/payments/'.$payment->bukti_transfer) }}" width="100"><br><br>
    @endif
    <input type="file" name="bukti_transfer" class="form-control">
</div>

<button class="btn btn-primary">Update</button>

</form>

@stop
