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
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);
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
            // Log or handle error
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
}