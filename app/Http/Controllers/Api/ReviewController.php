<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        if ($booking->client_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'done') {
            return response()->json(['message' => 'Booking must be done to leave a review'], 400);
        }

        $validated['client_id'] = auth()->id();
        $validated['freelancer_id'] = $booking->freelancer_id;

        $review = Review::create($validated);

        return response()->json($review, 201);
    }
}