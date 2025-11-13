<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\RequestBooking;
use App\Models\Transaction;

class CCAvenueCallbackController extends Controller
{
    public function success(Request $request)
    {
        $response = $request->all();
        $orderId = $response['order_id'] ?? null;

        $booking = Booking::where('order_id', $orderId)->first();
        $reqBooking = !$booking ? RequestBooking::where('order_id', $orderId)->first() : null;

        if ($booking) {
            $booking->update([
                'payment_status' => 'success',
                'payment_reference' => $response['reference_no'] ?? null,
                'payment_response' => json_encode($response)
            ]);
        } elseif ($reqBooking) {
            $reqBooking->update([
                'payment_status' => 'success',
                'payment_reference' => $response['reference_no'] ?? null,
                'payment_response' => json_encode($response)
            ]);
        }

        // Update transaction
        Transaction::where('transaction_id', $response['tracking_id'] ?? null)
            ->update([
                'status' => 'approved',
                'gateway_response' => json_encode($response)
            ]);

        return response()->json(['status' => 'success', 'message' => 'Payment successful']);
    }

    public function cancel(Request $request)
    {
        $response = $request->all();
        $orderId = $response['order_id'] ?? null;

        $booking = Booking::where('order_id', $orderId)->first();
        $reqBooking = !$booking ? RequestBooking::where('order_id', $orderId)->first() : null;

        if ($booking) {
            $booking->update(['payment_status' => 'failed', 'payment_response' => json_encode($response)]);
        } elseif ($reqBooking) {
            $reqBooking->update(['payment_status' => 'failed', 'payment_response' => json_encode($response)]);
        }

        Transaction::where('transaction_id', $response['tracking_id'] ?? null)
            ->update([
                'status' => 'failed',
                'gateway_response' => json_encode($response)
            ]);

        return response()->json(['status' => 'failed', 'message' => 'Payment cancelled']);
    }
}
