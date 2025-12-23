<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // =========================
    // BUAT PAYMENT
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'metode' => 'required|in:transfer,dana,ovo,gopay',
            'jumlah' => 'required|numeric',
        ]);

        $payment = Payment::create([
            'booking_id' => $request->booking_id,
            'metode' => $request->metode,
            'jumlah' => $request->jumlah,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'data' => $payment,
        ]);
    }

    // =========================
    // UPLOAD BUKTI TRANSFER
    // =========================
    public function uploadBuktiTransfer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // 🔥 FIX: SESUAIKAN DENGAN STRUCTURE KAMU (tamu_id)
        $payment = Payment::where('id', $id)
            ->whereHas('booking', function ($q) use ($request) {
                $q->where('tamu_id', $request->user()->tamu->id);
            })
            ->first();

        if (!$payment) {
            return response()->json([
                'message' => 'Payment tidak ditemukan',
            ], 404);
        }

        try {
            $file = $request->file('bukti_transfer');
            $filename = time() . '_bukti_' . $file->getClientOriginalName();
            $path = $file->storeAs('payments', $filename, 'public');

            $payment->update([
                'bukti_transfer' => $path,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bukti transfer berhasil diupload',
                'data' => $payment,
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => 'Gagal upload bukti transfer',
            ], 500);
        }
    }

    // =========================
    // LIST PAYMENT USER
    // =========================
    public function userPayments(Request $request)
    {
        $payments = Payment::with('booking')
            ->whereHas('booking', function ($q) use ($request) {
                $q->where('tamu_id', $request->user()->tamu->id);
            })
            ->latest()
            ->get();

        return response()->json($payments);
    }
}
