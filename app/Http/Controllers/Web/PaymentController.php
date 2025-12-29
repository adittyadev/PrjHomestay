<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Helpers\NotificationHelper;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('booking.tamu')->paginate(10);
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        // Load relasi tamu dan room supaya tampil di dropdown
        $bookings = Booking::with(['tamu', 'room'])
            ->where('status_booking', 'pending')
            ->orderBy('id', 'DESC')
            ->get();

        return view('payments.create', compact('bookings'));
    }
    public function store(Request $r)
    {
        $r->validate([
            'booking_id' => 'required',
            'metode' => 'required',
            'jumlah' => 'required|numeric',
            'bukti_transfer' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $filename = null;

        // Upload bukti transfer
        if ($r->bukti_transfer) {
            $filename = time() . '.' . $r->bukti_transfer->extension();
            $r->bukti_transfer->storeAs('payments', $filename, 'public');
        }

        Payment::create([
            'booking_id' => $r->booking_id,
            'metode' => $r->metode,
            'jumlah' => $r->jumlah,
            'bukti_transfer' => $filename,
            'status' => 'pending'
        ]);

        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        $bookings = Booking::all();
        return view('payments.edit', compact('payment', 'bookings'));
    }

    public function update(Request $r, $id)
    {
        $payment = Payment::findOrFail($id);

        $r->validate([
            'booking_id' => 'required',
            'metode' => 'required',
            'jumlah' => 'required|numeric',
            'status' => 'required',
            'bukti_transfer' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $filename = $payment->bukti_transfer;

        if ($r->bukti_transfer) {
            if ($filename && file_exists(storage_path('app/public/payments/' . $filename))) {
                unlink(storage_path('app/public/payments/' . $filename));
            }

            $filename = time() . '.' . $r->bukti_transfer->extension();
            $r->bukti_transfer->storeAs('payments', $filename, 'public');
        }

        if ($r->status == 'valid') {
            $booking = Booking::find($payment->booking_id);
            if ($booking) {
                $booking->update(['status_booking' => 'booked']);
            }
        }

        // 🔥 Jika admin set invalid → booking tetap pending
        if ($r->status == 'invalid') {
            $booking = Booking::find($payment->booking_id);
            if ($booking) {
                $booking->update(['status_booking' => 'pending']);
            }
        }

        $payment->update([
            'booking_id' => $r->booking_id,
            'metode' => $r->metode,
            'jumlah' => $r->jumlah,
            'status' => $r->status,
            'bukti_transfer' => $filename
        ]);

        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->bukti_transfer && file_exists(storage_path('app/public/payments/' . $payment->bukti_transfer))) {
            unlink(storage_path('app/public/payments/' . $payment->bukti_transfer));
        }

        $payment->delete();

        return back()->with('success', 'Pembayaran berhasil dihapus!');
    }

    public function updateStatus(Request $r, $id)
    {
        $payment = Payment::with('booking')->findOrFail($id);

        $r->validate([
            'status' => 'required|in:pending,valid,invalid'
        ]);

        // Update status pembayaran
        $payment->status = $r->status;
        $payment->save();

        if ($payment->booking) {
            // ✅ Jika pembayaran VALID
            if ($r->status === 'valid') {
                $payment->booking->update([
                    'status_booking' => 'booked'
                ]);

                // 🔔 BUAT NOTIFIKASI
                NotificationHelper::paymentConfirmed(
                    $payment->booking->user_id,
                    $payment->booking->id,
                    $payment->jumlah
                );
            }

            // ❌ Jika pembayaran INVALID
            if ($r->status === 'invalid') {
                $payment->booking->update([
                    'status_booking' => 'pending'
                ]);

                // 🔔 NOTIFIKASI DITOLAK
                NotificationHelper::paymentRejected(
                    $payment->booking->user_id,
                    $payment->booking->id,
                    'Bukti pembayaran tidak valid'
                );
            }
        }

        return redirect()
            ->route('payments.index')
            ->with('success', 'Status pembayaran & booking berhasil diperbarui!');
    }
}
