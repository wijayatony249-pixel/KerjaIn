<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
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

        $validated['client_id'] = auth()->id();
        $validated['status'] = 'pending';

        $booking = Booking::create($validated);

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