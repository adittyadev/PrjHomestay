@extends('adminlte::page')

@section('title', 'Data Kamar')

@section('content_header')
    <h1>Data Kamar</h1>
@stop

@section('content')

<a href="{{ route('rooms.create') }}" class="btn btn-primary mb-3">Tambah Kamar</a>

<table class="table table-bordered">
    <tr>
        <th>#</th>
        <th>Nama Kamar</th>
        <th>Kapasitas</th>
        <th>Harga</th>
        <th>Status</th>
        <th>Foto</th>
        <th>Aksi</th>
    </tr>

    @foreach ($rooms as $room)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $room->nama_kamar }}</td>
        <td>{{ $room->kapasitas }} orang</td>
        <td>Rp {{ number_format($room->harga) }}</td>
        <td>
            @if($room->status == 'available')
                <span class="badge bg-success">Available</span>
            @else
                <span class="badge bg-danger">Booked</span>
            @endif
        </td>
        <td>
            @if($room->foto)
                <img src="{{ asset('storage/' . $room->foto) }}" width="80">
            @else
                -
            @endif
        </td>
        <td>
            <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-warning btn-sm">Edit</a>

            <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" style="display:inline-block">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus kamar ini?')">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

@stop
