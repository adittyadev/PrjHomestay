@extends('adminlte::page')

@section('content')
<div class="container">
    <h4 class="mb-4">Booking Kamar</h4>

    <form method="POST" action="{{ route('booking.store') }}">
        @csrf

        <div class="mb-3">
            <label>Kamar</label>
            <select name="room_id" class="form-control" required>
                <option value="">-- Pilih Kamar --</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}">
                        {{ $room->nama_kamar }} - Rp {{ number_format($room->harga) }}/malam
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tanggal Check In</label>
            <input type="date" name="check_in" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tanggal Check Out</label>
            <input type="date" name="check_out" class="form-control" required>
        </div>

        <button class="btn btn-primary">
            Konfirmasi Booking
        </button>
    </form>
</div>
@endsection
