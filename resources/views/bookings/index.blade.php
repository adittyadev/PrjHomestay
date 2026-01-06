@extends('adminlte::page')

@section('title', 'Data Booking')

@section('content_header')
    <h1>Data Booking</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

{{-- Tombol Tambah Booking - Hapus jika admin tidak boleh menambah booking --}}
{{-- <a href="{{ route('bookings.create') }}" class="btn btn-primary mb-3">
    <i class="fas fa-plus"></i> Tambah Booking
</a> --}}

<div class="card">
    <div class="card-body">
        <table id="bookingTable" class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Tamu</th>
                    <th>Kamar</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Total Bayar</th>
                    <th>Status Booking</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $b->tamu->nama ?? '-' }}</td>
                    <td>{{ $b->room->nama_kamar ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($b->check_in)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($b->check_out)->format('d/m/Y') }}</td>
                    <td>Rp {{ number_format($b->total_bayar, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge
                            @if($b->status_booking == 'pending') badge-warning
                            @elseif($b->status_booking == 'booked') badge-info
                            @elseif($b->status_booking == 'checkin') badge-secondary
                            @elseif($b->status_booking == 'checkout') badge-primary
                            @elseif($b->status_booking == 'selesai') badge-success
                            @elseif($b->status_booking == 'cancelled') badge-danger
                            @endif">
                            {{ strtoupper($b->status_booking) }}
                        </span>
                    </td>
                    <td>
                        {{-- Tombol Lihat Detail --}}
                        <a href="{{ route('bookings.show', $b->id) }}"
                           class="btn btn-sm btn-info mb-1">
                            <i class="fas fa-eye"></i> Detail
                        </a>

                        {{-- Tombol Update Status untuk CHECKOUT --}}
                        @if($b->status_booking == 'checkout')
                            <form action="{{ route('bookings.updateStatus', $b->id) }}"
                                  method="POST" 
                                  class="d-inline-block booking-form">
                                @csrf
                                <input type="hidden" name="status_booking" value="selesai">
                                <button type="button"
                                    class="btn btn-sm btn-success mb-1 btn-booking-action"
                                    data-action="selesai"
                                    data-name="{{ $b->tamu->nama ?? '-' }}"
                                    data-room="{{ $b->room->nama_kamar ?? '-' }}"
                                    data-booking="{{ $b->id }}">
                                    <i class="fas fa-check"></i> Selesai
                                </button>
                            </form>

                            <form action="{{ route('bookings.updateStatus', $b->id) }}"
                                  method="POST" 
                                  class="d-inline-block booking-form">
                                @csrf
                                <input type="hidden" name="status_booking" value="cancelled">
                                <button type="button"
                                    class="btn btn-sm btn-danger mb-1 btn-booking-action"
                                    data-action="cancelled"
                                    data-name="{{ $b->tamu->nama ?? '-' }}"
                                    data-room="{{ $b->room->nama_kamar ?? '-' }}"
                                    data-booking="{{ $b->id }}">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </form>
                        @endif

                        {{-- Tombol Hapus - Hapus jika admin tidak boleh menghapus booking --}}
                        {{-- <form action="{{ route('bookings.destroy', $b->id) }}"
                              method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-secondary mb-1"
                                onclick="return confirm('Hapus booking ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form> --}}

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">
                        <em>Data booking belum tersedia</em>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@stop

@section('css')
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <style>
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
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .modern-confirm-header.selesai {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
        }
        
        .modern-confirm-header.cancelled {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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
            color: white;
        }
        
        .modern-confirm-btn-confirm.selesai {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
        }
        
        .modern-confirm-btn-confirm.cancelled {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }
        
        .modern-confirm-btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
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
    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#bookingTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
                },
                "order": [[0, "desc"]],
                "pageLength": 10,
                "responsive": true,
                "autoWidth": false
            });

            // Modern Confirm Dialog for Booking Actions
            let currentForm = null;

            $('.btn-booking-action').on('click', function() {
                const action = $(this).data('action');
                const name = $(this).data('name');
                const room = $(this).data('room');
                const booking = $(this).data('booking');
                currentForm = $(this).closest('form');

                let title, message, icon, headerClass, btnClass, btnText;

                if (action === 'selesai') {
                    title = 'Tandai Selesai';
                    message = 'Apakah Anda yakin ingin menandai booking ini sebagai SELESAI? Booking telah selesai dan tamu sudah checkout dengan baik.';
                    icon = 'fa-check-circle';
                    headerClass = 'selesai';
                    btnClass = 'selesai';
                    btnText = 'Ya, Tandai Selesai';
                } else if (action === 'cancelled') {
                    title = 'Batalkan Booking';
                    message = 'Apakah Anda yakin ingin MEMBATALKAN booking ini? Tindakan ini menandakan booking dibatalkan.';
                    icon = 'fa-times-circle';
                    headerClass = 'cancelled';
                    btnClass = 'cancelled';
                    btnText = 'Ya, Batalkan';
                }

                const modalHTML = `
                    <div class="modern-confirm-overlay" id="confirmModal">
                        <div class="modern-confirm-box">
                            <div class="modern-confirm-header ${headerClass}">
                                <div class="modern-confirm-icon">
                                    <i class="fas ${icon}"></i>
                                </div>
                                <h3 class="modern-confirm-title">${title}</h3>
                            </div>
                            <div class="modern-confirm-body">
                                <p class="modern-confirm-message">${message}</p>
                                <div class="modern-confirm-details">
                                    <div class="modern-confirm-detail-item">
                                        <span class="modern-confirm-detail-label">ID Booking:</span>
                                        <span class="modern-confirm-detail-value">#${booking}</span>
                                    </div>
                                    <div class="modern-confirm-detail-item">
                                        <span class="modern-confirm-detail-label">Nama Tamu:</span>
                                        <span class="modern-confirm-detail-value">${name}</span>
                                    </div>
                                    <div class="modern-confirm-detail-item">
                                        <span class="modern-confirm-detail-label">Kamar:</span>
                                        <span class="modern-confirm-detail-value">${room}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="modern-confirm-footer">
                                <button type="button" class="modern-confirm-btn modern-confirm-btn-cancel" onclick="closeConfirmModal()">
                                    <i class="fas fa-times"></i> Batal
                                </button>
                                <button type="button" class="modern-confirm-btn modern-confirm-btn-confirm ${btnClass}" onclick="confirmBookingAction()">
                                    <i class="fas fa-check"></i> ${btnText}
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

            window.confirmBookingAction = function() {
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