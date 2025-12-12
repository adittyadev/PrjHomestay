@extends('adminlte::page')

@section('title', 'Data Pembayaran')

@section('content_header')
    <h1>Data Pembayaran</h1>
@stop

@section('content')

<a href="{{ route('payments.create') }}" class="btn btn-primary mb-3">+ Tambah Pembayaran</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Booking</th>
            <th>Tamu</th>
            <th>Jumlah</th>
            <th>Status</th>
            <th>Bukti</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payments as $p)
        <tr>
            <td>{{ $p->id }}</td>
            <td>#{{ $p->booking_id }}</td>
            <td>{{ $p->booking->tamu->nama }}</td>
            <td>Rp {{ number_format($p->jumlah) }}</td>
            <td>
                <span class="badge badge-{{ $p->status == 'valid' ? 'success' : ($p->status == 'invalid' ? 'danger' : 'warning') }}">
                    {{ ucfirst($p->status) }}
                </span>
            </td>
            <td>
                @if($p->bukti_transfer)
                <img src="{{ asset('storage/payments/'.$p->bukti_transfer) }}" width="70">
                @else
                -
                @endif
            </td>
<td>

    <!-- Tombol Status VALID -->
    <form action="{{ route('payments.status', $p->id) }}" method="POST" class="d-inline">
        @csrf
        <input type="hidden" name="status" value="valid">
        <button class="btn btn-success btn-sm" 
            onclick="return confirm('Ubah status menjadi VALID?')">
            Valid
        </button>
    </form>

    <!-- Tombol Status INVALID -->
    <form action="{{ route('payments.status', $p->id) }}" method="POST" class="d-inline">
        @csrf
        <input type="hidden" name="status" value="invalid">
        <button class="btn btn-danger btn-sm"
            onclick="return confirm('Ubah status menjadi INVALID?')">
            Invalid
        </button>
    </form>

    <!-- Tombol Status PENDING -->
    <form action="{{ route('payments.status', $p->id) }}" method="POST" class="d-inline">
        @csrf
        <input type="hidden" name="status" value="pending">
        <button class="btn btn-warning btn-sm"
            onclick="return confirm('Kembalikan status menjadi PENDING?')">
            Pending
        </button>
    </form>

    <br>

    <!-- Tombol Edit -->
    <a href="{{ route('payments.edit', $p->id) }}" class="btn btn-primary btn-sm mt-1">Edit</a>

    <!-- Tombol Hapus -->
    <form action="{{ route('payments.destroy', $p->id) }}" method="POST" class="d-inline">
        @csrf @method('DELETE')
        <button onclick="return confirm('Hapus pembayaran?')" class="btn btn-dark btn-sm mt-1">Hapus</button>
    </form>

</td>

        </tr>
        @endforeach
    </tbody>
</table>

@stop
