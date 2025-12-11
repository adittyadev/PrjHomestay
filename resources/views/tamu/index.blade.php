@extends('adminlte::page')

@section('title', 'Data Tamu')

@section('content_header')
    <h1>Data Tamu</h1>
@stop

@section('content')

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('tamu.create') }}" class="btn btn-primary mb-3">
    <i class="fas fa-plus"></i> Tambah Tamu
</a>

<div class="card">
    <div class="card-body">

        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                    <th width="140px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $t)
                <tr>
                    <td>{{ $t->id }}</td>
                    <td>{{ $t->nama }}</td>
                    <td>{{ $t->email }}</td>
                    <td>{{ $t->no_hp }}</td>
                    <td>{{ $t->alamat }}</td>
                    <td>
                        <a href="{{ route('tamu.edit', $t->id) }}"
                           class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>

                        <form action="{{ route('tamu.destroy', $t->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Hapus data ini?')"
                                    class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

@stop
