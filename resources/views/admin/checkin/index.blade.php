@extends('adminlte::page')

@section('title', 'Check-In Tamu')

@section('content_header')
    <h1>
        <i class="fas fa-sign-in-alt"></i> Daftar Check-In Tamu
    </h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

<div class="card">
    <div class="card-header bg-primary">
        <h3 class="card-title">
            <i class="fas fa-list"></i> Daftar Tamu Siap Check-In
        </h3>
    </div>
    <div class="card-body">
        <table id="checkinTable" class="table table-bordered table-hover table-striped">
            <thead class="thead-dark">
                <tr>
                    <th width="50">#</th>
                    <th>Tamu</th>
                    <th>Kamar</th>
                    <th>Check-In</th>
                    <th>Check-Out</th>
                    <th>Total Bayar</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $b)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $b->tamu->nama }}</strong><br>
                            <small class="text-muted">
                                <i class="fas fa-id-card"></i> {{ $b->tamu->nik ?? '-' }}
                            </small>
                        </td>
                        <td>
                            <span class="badge badge-info badge-lg">
                                {{ $b->room->nama_kamar }}
                            </span><br>
                            <small class="text-muted">No: {{ $b->room->nomor_kamar ?? '-' }}</small>
                        </td>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($b->check_in)->format('d/m/Y') }}</strong><br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($b->check_in)->format('l') }}</small>
                        </td>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($b->check_out)->format('d/m/Y') }}</strong><br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($b->check_out)->format('l') }}</small>
                        </td>
                        <td>
                            <strong class="text-success">
                                Rp {{ number_format($b->total_bayar, 0, ',', '.') }}
                            </strong>
                        </td>
                        <td class="text-center">
                            <form action="{{ route('admin.checkin.proses', $b->id) }}" 
                                  method="POST" 
                                  class="checkin-form">
                                @csrf
                                <button type="button" 
                                        class="btn btn-success btn-sm btn-checkin"
                                        data-name="{{ $b->tamu->nama }}"
                                        data-room="{{ $b->room->nama_kamar }}"
                                        data-checkin="{{ \Carbon\Carbon::parse($b->check_in)->format('d/m/Y') }}">
                                    <i class="fas fa-sign-in-alt"></i> Check-In
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach

                @if (count($bookings) == 0)
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i><br>
                            <strong class="text-muted">Tidak ada tamu yang siap check-in</strong>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <style>
        .badge-lg {
            font-size: 0.9rem;
            padding: 0.4rem 0.8rem;
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
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
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
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
            color: white;
        }
        
        .modern-confirm-btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
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
            $('#checkinTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
                },
                "order": [[3, "asc"]],
                "pageLength": 10,
                "responsive": true,
                "autoWidth": false
            });

            // Modern Confirm Dialog
            let currentForm = null;

            $('.btn-checkin').on('click', function() {
                const name = $(this).data('name');
                const room = $(this).data('room');
                const checkin = $(this).data('checkin');
                currentForm = $(this).closest('form');

                // Create modal HTML
                const modalHTML = `
                    <div class="modern-confirm-overlay" id="confirmModal">
                        <div class="modern-confirm-box">
                            <div class="modern-confirm-header">
                                <div class="modern-confirm-icon">
                                    <i class="fas fa-sign-in-alt"></i>
                                </div>
                                <h3 class="modern-confirm-title">Konfirmasi Check-In</h3>
                            </div>
                            <div class="modern-confirm-body">
                                <p class="modern-confirm-message">
                                    Apakah Anda yakin ingin melakukan check-in untuk tamu ini?
                                </p>
                                <div class="modern-confirm-details">
                                    <div class="modern-confirm-detail-item">
                                        <span class="modern-confirm-detail-label">Nama Tamu:</span>
                                        <span class="modern-confirm-detail-value">${name}</span>
                                    </div>
                                    <div class="modern-confirm-detail-item">
                                        <span class="modern-confirm-detail-label">Kamar:</span>
                                        <span class="modern-confirm-detail-value">${room}</span>
                                    </div>
                                    <div class="modern-confirm-detail-item">
                                        <span class="modern-confirm-detail-label">Tanggal Check-In:</span>
                                        <span class="modern-confirm-detail-value">${checkin}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="modern-confirm-footer">
                                <button type="button" class="modern-confirm-btn modern-confirm-btn-cancel" onclick="closeConfirmModal()">
                                    <i class="fas fa-times"></i> Batal
                                </button>
                                <button type="button" class="modern-confirm-btn modern-confirm-btn-confirm" onclick="confirmCheckin()">
                                    <i class="fas fa-check"></i> Ya, Check-In
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                // Remove existing modal if any
                $('#confirmModal').remove();
                
                // Append to body
                $('body').append(modalHTML);
                
                // Show modal
                $('#confirmModal').fadeIn(300);
            });

            // Close modal function
            window.closeConfirmModal = function() {
                $('#confirmModal').fadeOut(300, function() {
                    $(this).remove();
                });
                currentForm = null;
            };

            // Confirm checkin function
            window.confirmCheckin = function() {
                if (currentForm) {
                    currentForm.submit();
                }
            };

            // Close on overlay click
            $(document).on('click', '.modern-confirm-overlay', function(e) {
                if (e.target === this) {
                    closeConfirmModal();
                }
            });

            // Close on ESC key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $('#confirmModal').length) {
                    closeConfirmModal();
                }
            });
        });
    </script>
@stop