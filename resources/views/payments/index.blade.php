@extends('adminlte::page')

@section('title', 'Data Pembayaran')

@section('content_header')
    <h1>Data Pembayaran</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

<a href="{{ route('payments.create') }}" class="btn btn-primary mb-3">
    <i class="fas fa-plus"></i> Tambah Pembayaran
</a>

<div class="card">
    <div class="card-body">
        <table id="paymentTable" class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>ID Booking</th>
                    <th>Tamu</th>
                    <th>Kamar</th>
                    <th>Jumlah Bayar</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Bukti Transfer</th>
                    <th width="220">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payments as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>#{{ $p->booking_id }}</td>
                    <td>
                        {{ $p->booking->tamu->nama ?? '-' }}<br>
                        <small class="text-muted">{{ $p->booking->tamu->no_hp ?? '-' }}</small>
                    </td>
                    <td>{{ $p->booking->room->nama_kamar ?? '-' }}</td>
                    <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge badge-secondary">
                            {{ strtoupper($p->metode ?? 'TRANSFER') }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-lg
                            @if($p->status == 'valid') badge-success
                            @elseif($p->status == 'invalid') badge-danger
                            @else badge-warning
                            @endif">
                            {{ strtoupper($p->status) }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($p->bukti_transfer && file_exists(public_path('storage/'.$p->bukti_transfer)))
                            <a href="{{ asset('storage/'.$p->bukti_transfer) }}" target="_blank">
                                <img src="{{ asset('storage/'.$p->bukti_transfer) }}" width="60" class="img-thumbnail">
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('payments.show', $p->id) }}" 
                           class="btn btn-info btn-sm mb-1">
                            <i class="fas fa-eye"></i> Detail
                        </a>

                        @if($p->status == 'pending')
<form action="{{ route('payments.status', $p->id) }}" method="POST" class="d-inline payment-form">
    @csrf
    <input type="hidden" name="status" value="valid">
    <button type="button"
        class="btn btn-success btn-sm mb-1 btn-payment-action"
        data-action="valid"
        data-name="{{ $p->booking->tamu->nama }}"
        data-booking="{{ $p->booking_id }}"
        data-amount="{{ number_format($p->jumlah, 0, ',', '.') }}"
        data-room="{{ $p->booking->room->nama_kamar ?? '-' }}">
        <i class="fas fa-check"></i> Valid
    </button>
</form>

<form action="{{ route('payments.status', $p->id) }}" method="POST" class="d-inline payment-form">
    @csrf
    <input type="hidden" name="status" value="invalid">
    <button type="button"
        class="btn btn-danger btn-sm mb-1 btn-payment-action"
        data-action="invalid"
        data-name="{{ $p->booking->tamu->nama }}"
        data-booking="{{ $p->booking_id }}"
        data-amount="{{ number_format($p->jumlah, 0, ',', '.') }}"
        data-room="{{ $p->booking->room->nama_kamar ?? '-' }}">
        <i class="fas fa-times"></i> Invalid
    </button>
</form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">Data pembayaran belum tersedia</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Konfirmasi Payment -->
<div class="modal fade" id="confirmPaymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 15px; overflow: hidden;">
            <div id="modalHeader" class="text-center py-4" style="background: #28a745;">
                <div class="mb-3">
                    <i class="fas fa-check-circle" style="font-size: 60px; color: white;"></i>
                </div>
                <h3 class="mb-0" style="color: white; font-weight: bold;" id="modalTitle">Tandai Valid</h3>
            </div>
            <div class="modal-body px-4 py-4">
                <p class="text-center mb-4" id="modalMessage" style="color: #666; font-size: 15px;">
                    Apakah Anda yakin ingin menandai pembayaran ini sebagai VALID? 
                    Pembayaran telah selesai dan tamu sudah checkout dengan baik.
                </p>
                <div class="info-row mb-2">
                    <span style="color: #888; font-size: 14px;">ID Booking:</span>
                    <span class="float-right" style="font-weight: bold; color: #333;" id="modalBookingId">#4</span>
                </div>
                <div class="info-row mb-2">
                    <span style="color: #888; font-size: 14px;">Nama Tamu:</span>
                    <span class="float-right" style="font-weight: bold; color: #333;" id="modalTamuName">Adit</span>
                </div>
                <div class="info-row mb-2">
                    <span style="color: #888; font-size: 14px;">Kamar:</span>
                    <span class="float-right" style="font-weight: bold; color: #333;" id="modalRoom">Kamar 1</span>
                </div>
                <div class="info-row mb-4">
                    <span style="color: #888; font-size: 14px;">Jumlah:</span>
                    <span class="float-right" style="font-weight: bold; color: #333;" id="modalAmount">Rp 500.000</span>
                </div>
                <div class="row">
                    <div class="col-6 pr-2">
                        <button type="button" class="btn btn-block" id="btnCancel" data-dismiss="modal" 
                                style="background: #6c757d; color: white; border-radius: 8px; padding: 12px; font-weight: 500;">
                            <i class="fas fa-times"></i> Batal
                        </button>
                    </div>
                    <div class="col-6 pl-2">
                        <button type="button" class="btn btn-block" id="btnConfirm"
                                style="background: #28a745; color: white; border-radius: 8px; padding: 12px; font-weight: 500;">
                            <i class="fas fa-check"></i> Ya, Tandai Valid
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script>
let currentForm = null;

document.querySelectorAll('.btn-payment-action').forEach(button => {
    button.addEventListener('click', function () {
        const action  = this.dataset.action;
        const name    = this.dataset.name;
        const booking = this.dataset.booking;
        const amount  = this.dataset.amount;
        const room    = this.dataset.room;

        currentForm = this.closest('form');

        // Update modal content based on action
        const modal = document.getElementById('confirmPaymentModal');
        const modalHeader = document.getElementById('modalHeader');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const btnConfirm = document.getElementById('btnConfirm');

        if (action === 'valid') {
            modalHeader.style.background = '#28a745';
            modalTitle.textContent = 'Tandai Valid';
            modalMessage.textContent = 'Apakah Anda yakin ingin menandai pembayaran ini sebagai VALID? Pembayaran telah selesai dan tamu sudah checkout dengan baik.';
            btnConfirm.style.background = '#28a745';
            btnConfirm.innerHTML = '<i class="fas fa-check"></i> Ya, Tandai Valid';
        } else {
            modalHeader.style.background = '#dc3545';
            modalTitle.textContent = 'Tandai Invalid';
            modalMessage.textContent = 'Apakah Anda yakin ingin menandai pembayaran ini sebagai INVALID? Pembayaran akan ditolak dan perlu ditinjau kembali.';
            btnConfirm.style.background = '#dc3545';
            btnConfirm.innerHTML = '<i class="fas fa-times"></i> Ya, Tandai Invalid';
        }

        // Update booking info
        document.getElementById('modalBookingId').textContent = '#' + booking;
        document.getElementById('modalTamuName').textContent = name;
        document.getElementById('modalRoom').textContent = room;
        document.getElementById('modalAmount').textContent = 'Rp ' + amount;

        // Show modal
        $('#confirmPaymentModal').modal('show');
    });
});

// Handle confirm button
document.getElementById('btnConfirm').addEventListener('click', function() {
    if (currentForm) {
        currentForm.submit();
    }
});
</script>
@endsection