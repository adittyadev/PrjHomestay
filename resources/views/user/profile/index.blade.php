@extends('adminlte::page')

@section('title', 'Profil Saya')

@section('content_header')
    <h1>Profil Saya</h1>
@endsection

@section('content')
<div class="container">

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card card-outline card-primary shadow-sm">
                <div class="card-body">

                    {{-- HEADER PROFILE --}}
                    <div class="text-center mb-4">
                        <div class="mb-2">
                            <i class="fas fa-user-circle fa-5x text-primary"></i>
                        </div>
                        <h4 class="mb-0">{{ $tamu->nama }}</h4>
                        <small class="text-muted">{{ $tamu->email }}</small>
                    </div>

                    <hr>

                    {{-- INFO PROFILE --}}
                    <div class="row mb-2">
                        <div class="col-4 text-muted">Nomor HP</div>
                        <div class="col-8">{{ $tamu->no_hp ?: '-' }}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-4 text-muted">Alamat</div>
                        <div class="col-8">{{ $tamu->alamat ?: '-' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-4 text-muted">Bergabung Sejak</div>
                        <div class="col-8">
                            {{ $tamu->created_at->format('d M Y') }}
                        </div>
                    </div>

                    <hr>

                    {{-- ACTION --}}
                    <div class="text-end">
                        <a href="{{ route('user.profile.edit') }}"
                           class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i>
                            Edit Profil
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection
