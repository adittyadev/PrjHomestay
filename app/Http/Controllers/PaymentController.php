<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        return Payment::with('booking')->get();
    }

    public function store(Request $request)
    {
        $payment = Payment::create([
            'booking_id'     => $request->booking_id,
            'metode'         => $request->metode,
            'jumlah'         => $request->jumlah,
            'bukti_transfer' => $request->bukti_transfer,
            'status'         => 'pending'
        ]);

        return response()->json($payment, 201);
    }

    public function show($id)
    {
        return Payment::with('booking')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        // Jika admin memvalidasi pembayaran
        if ($request->has('status')) {
            $payment->status = $request->status;
        }

        $payment->update($request->all());
        return response()->json($payment);
    }

    public function destroy($id)
    {
        Payment::destroy($id);
        return response()->json(['message' => 'Payment deleted']);
    }
}
