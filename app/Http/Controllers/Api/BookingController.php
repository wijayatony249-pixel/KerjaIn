<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $bookings = Booking::with(['service', 'client:id,name,avatar', 'freelancer:id,name,avatar'])
            ->where(function ($q) use ($user) {
                $q->where('client_id', $user->id)
                  ->orWhere('freelancer_id', $user->id);
            })
            ->latest()->get();

        return response()->json($bookings);
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id'   => 'required|exists:services,id',
            'booking_date' => 'required|date|after:today',
            'note'         => 'nullable|string',
        ]);

        $service = \App\Models\Service::findOrFail($request->service_id);

        $booking = Booking::create([
            'service_id' => $request->service_id,
            'freelancer_id' => $service->freelancer_id,
            'client_id' => auth()->id(),
            'booking_date' => $request->booking_date,
            'status' => 'pending',
        ]);

        return response()->json($booking->load(['service', 'freelancer:id,name']), 201);
    }

    public function show(Booking $booking)
    {
        return response()->json($booking->load(['service', 'client:id,name,avatar', 'freelancer:id,name,avatar', 'messages', 'review']));
    }

    public function update(Request $request, Booking $booking)
    {
        // Hanya freelancer yang bisa update status
        if ($booking->freelancer_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected,done',
        ]);

        $booking->update(['status' => $request->status]);
        return response()->json($booking);
    }

    public function destroy(Booking $booking)
    {
        return response()->json(['message' => 'Not allowed'], 403);
    }
}