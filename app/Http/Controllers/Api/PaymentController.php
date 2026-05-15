<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function callback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                // BOOK-ID-TIME
                $orderIdParts = explode('-', $request->order_id);
                $bookingId = $orderIdParts[1];
                
                $booking = \App\Models\Booking::find($bookingId);
                if ($booking) {
                    $booking->update(['payment_status' => 'paid']);
                }
            }
        }
        
        return response()->json(['status' => 'success']);
    }
}
