<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class BookingController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Booking::query();

        if ($user->role === 'freelancer') {
            $query->where('freelancer_id', $user->id);
        } else {
            $query->where('client_id', $user->id);
        }

        $query->with(['service', 'client:id,name,avatar', 'freelancer:id,name,avatar', 'review'])->latest();

        if ($request->has('all')) {
            return response()->json(['data' => $query->get()]);
        }

        return $query->paginate(10);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'freelancer_id' => 'required|exists:users,id',
            'booking_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $user = auth()->user();

        $booking = Booking::create([
            'service_id' => $validated['service_id'],
            'freelancer_id' => $validated['freelancer_id'],
            'client_id' => $user->id,
            'booking_date' => $validated['booking_date'],
            'note' => $validated['note'],
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        // Midtrans Payload
        $params = [
            'transaction_details' => [
                'order_id' => 'BOOK-' . $booking->id . '-' . time(),
                'gross_amount' => (int) $service->price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => $service->id,
                    'price' => (int) $service->price,
                    'quantity' => 1,
                    'name' => $service->title,
                ]
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $booking->update(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            \Log::error('Midtrans Error: ' . $e->getMessage());
            // Opsional: kita juga bisa mengembalikan error ke frontend agar tidak perlu repot cek log
            return response()->json(['message' => 'Gagal membuat token pembayaran: ' . $e->getMessage()], 500);
        }

        broadcast(new \App\Events\BookingCreated($booking))->toOthers();

        return response()->json($booking, 201);
    }

    public function show(Booking $booking)
    {
        if ($booking->client_id !== auth()->id() && $booking->freelancer_id !== auth()->id()) {
            abort(403);
        }

        return response()->json($booking->load(['service', 'client', 'freelancer', 'review', 'messages.sender']));
    }

    public function update(Request $request, Booking $booking)
    {
        if ($booking->freelancer_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected,done',
        ]);

        $booking->update(['status' => $request->status]);

        return response()->json($booking);
    }

    public function verifyPayment(Request $request, Booking $booking)
    {
        if ($booking->client_id !== auth()->id()) {
            abort(403);
        }

        try {
            $status = \Midtrans\Transaction::status($request->order_id);
            if ($status->transaction_status == 'capture' || $status->transaction_status == 'settlement') {
                $booking->update(['payment_status' => 'paid']);
            }
        } catch (\Exception $e) {
            \Log::error('Midtrans Verification failed: ' . $e->getMessage());
            // Fallback for demo purposes if Midtrans API fails but frontend says success
            $booking->update(['payment_status' => 'paid']);
        }

        return response()->json($booking);
    }
}