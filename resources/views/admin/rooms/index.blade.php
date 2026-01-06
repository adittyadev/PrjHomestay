@extends('adminlte::page')

@section('title', 'Data Kamar')

@section('content_header')
    <h1>
        <i class="fas fa-bed"></i> Data Kamar
    </h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

<a href="{{ route('rooms.create') }}" class="btn btn-primary mb-3">
    <i class="fas fa-plus"></i> Tambah Kamar
</a>

<div class="card">
    <div class="card-header bg-primary">
        <h3 class="card-title">
            <i class="fas fa-list"></i> Daftar Kamar Hotel
        </h3>
    </div>
    <div class="card-body">
        <table id="roomTable" class="table table-bordered table-hover table-striped">
            <thead class="thead-dark">
                <tr>
                    <th width="50">#</th>
                    <th>Foto</th>
                    <th>Nama Kamar</th>
                    <th>Kapasitas</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rooms as $room)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">
                        @if($room->foto)
                            @php
                                $imagePath = null;
                                $possiblePaths = [
                                    'storage/' . $room->foto,
                                    $room->foto
                                ];
                                
                                foreach ($possiblePaths as $path) {
                                    if (file_exists(public_path($path))) {
                                        $imagePath = asset($path);
                                        break;
                                    }
                                }
                            @endphp
                            
                            @if($imagePath)
                                <a href="{{ $imagePath }}" target="_blank" data-toggle="tooltip" title="Lihat Foto">
                                    <img src="{{ $imagePath }}" 
                                         width="80" 
                                         class="img-thumbnail room-img"
                                         alt="{{ $room->nama_kamar }}"
                                         onerror="this.src='https://via.placeholder.com/80x80?text=No+Image'">
                                </a>
                            @else
                                <img src="https://via.placeholder.com/80x80?text=No+Image" 
                                     width="80" 
                                     class="img-thumbnail"
                                     alt="No Image">
                            @endif
                        @else
                            <img src="https://via.placeholder.com/80x80?text=No+Image" 
                                 width="80" 
                                 class="img-thumbnail"
                                 alt="No Image">
                        @endif
                    </td>
                    <td>
                        <strong>{{ $room->nama_kamar }}</strong><br>
                    </td>
                    <td class="text-center">
                        <i class="fas fa-user"></i> {{ $room->kapasitas }} Orang
                    </td>
                    <td>
                        <strong class="text-success">
                            Rp {{ number_format($room->harga, 0, ',', '.') }}
                        </strong><br>
                        <small class="text-muted">per malam</small>
                    </td>
                    <td class="text-center">
                        @if($room->status == 'available' || $room->status == 'tersedia')
                            <span class="badge badge-success badge-lg">
                                <i class="fas fa-check-circle"></i> Tersedia
                            </span>
                        @elseif($room->status == 'terisi' || $room->status == 'occupied')
                            <span class="badge badge-danger badge-lg">
                                <i class="fas fa-times-circle"></i> Terisi
                            </span>
                        @else
                            <span class="badge badge-secondary badge-lg">
                                {{ $room->status }}
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        {{-- Edit --}}
                        <a href="{{ route('rooms.edit', $room->id) }}" 
                           class="btn btn-warning btn-sm mb-1"
                           data-toggle="tooltip" 
                           title="Edit Kamar">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        {{-- Hapus --}}
                        <form action="{{ route('rooms.destroy', $room->id) }}" 
                              method="POST" 
                              class="d-inline room-delete-form">
                            @csrf 
                            @method('DELETE')
                            <button type="button"
                                    class="btn btn-danger btn-sm mb-1 btn-room-delete"
                                    data-name="{{ $room->nama_kamar }}"
                                    data-number="{{ $room->nomor_kamar ?? '-' }}"
                                    data-toggle="tooltip" 
                                    title="Hapus Kamar">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i><br>
                        <strong class="text-muted">Belum ada data kamar</strong>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <style>
        .badge-lg {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
        }
        
        .room-img {
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .room-img:hover {
            transform: scale(1.1);
        }

        /* Modern Confirm Modal */
        .modern-confirm-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s ease;
        }
        
        .modern-confirm-box {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.9);
            background: white;
            border-radius: 20px;
            padding: 0;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            z-index: 10000;
            animation: slideUp 0.3s ease forwards;
            overflow: hidden;
        }
        
        .modern-confirm-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .modern-confirm-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 40px;
        }
        
        .modern-confirm-title {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }
        
        .modern-confirm-body {
            padding: 30px;
            text-align: center;
        }
        
        .modern-confirm-message {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
            line-height: 1.6;
        }
        
        .modern-confirm-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
            text-align: left;
        }
        
        .modern-confirm-detail-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .modern-confirm-detail-item:last-child {
            border-bottom: none;
        }
        
        .modern-confirm-detail-label {
            color: #6c757d;
            font-weight: 600;
        }
        
        .modern-confirm-detail-value {
            color: #212529;
            font-weight: 700;
        }
        
        .modern-confirm-footer {
            padding: 0 30px 30px;
            display: flex;
            gap: 15px;
        }
        
        .modern-confirm-btn {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .modern-confirm-btn-cancel {
            background: #6c757d;
            color: white;
        }
        
        .modern-confirm-btn-cancel:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        }
        
        .modern-confirm-btn-confirm {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }
        
        .modern-confirm-btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from {
                transform: translate(-50%, -50%) scale(0.9);
                opacity: 0;
            }
            to {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#roomTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
                },
                "order": [[0, "asc"]],
                "pageLength": 10,
                "responsive": true,
                "autoWidth": false
            });

            // Initialize Tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Modern Confirm Dialog for Delete
            let currentForm = null;

            $('.btn-room-delete').on('click', function() {
                const name = $(this).data('name');
                const number = $(this).data('number');
                currentForm = $(this).closest('form');

                const modalHTML = `
                    <div class="modern-confirm-overlay" id="confirmModal">
                        <div class="modern-confirm-box">
                            <div class="modern-confirm-header">
                                <div class="modern-confirm-icon">
                                    <i class="fas fa-trash-alt"></i>
                                </div>
                                <h3 class="modern-confirm-title">Hapus Kamar</h3>
                            </div>
                            <div class="modern-confirm-body">
                                <p class="modern-confirm-message">
                                    Apakah Anda yakin ingin menghapus kamar ini? Data yang sudah dihapus tidak dapat dikembalikan.
                                </p>
                                <div class="modern-confirm-details">
                                    <div class="modern-confirm-detail-item">
                                        <span class="modern-confirm-detail-label">Nama Kamar:</span>
                                        <span class="modern-confirm-detail-value">${name}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="modern-confirm-footer">
                                <button type="button" class="modern-confirm-btn modern-confirm-btn-cancel" onclick="closeConfirmModal()">
                                    <i class="fas fa-times"></i> Batal
                                </button>
                                <button type="button" class="modern-confirm-btn modern-confirm-btn-confirm" onclick="confirmDelete()">
                                    <i class="fas fa-trash"></i> Ya, Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                $('#confirmModal').remove();
                $('body').append(modalHTML);
                $('#confirmModal').fadeIn(300);
            });

            window.closeConfirmModal = function() {
                $('#confirmModal').fadeOut(300, function() {
                    $(this).remove();
                });
                currentForm = null;
            };

            window.confirmDelete = function() {
                if (currentForm) {
                    currentForm.submit();
                }
            };

            $(document).on('click', '.modern-confirm-overlay', function(e) {
                if (e.target === this) {
                    closeConfirmModal();
                }
            });

            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $('#confirmModal').length) {
                    closeConfirmModal();
                }
            });
        });
    </script>
@stop