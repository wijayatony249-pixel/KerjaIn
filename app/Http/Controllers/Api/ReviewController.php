<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return response()->json(Review::with(['client:id,name,avatar'])->latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id|unique:reviews,booking_id',
            'rating'     => 'required|integer|between:1,5',
            'comment'    => 'nullable|string',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        if ($booking->status !== 'done') {
            return response()->json(['message' => 'Booking belum selesai'], 422);
        }

        if ($booking->client_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $review = Review::create([
            'booking_id'    => $request->booking_id,
            'client_id'     => auth()->id(),
            'rating'        => $request->rating,
            'comment'       => $request->comment,
        ]);

        return response()->json($review, 201);
    }

    public function show(Review $review)
    {
        return response()->json($review->load('client:id,name,avatar'));
    }

    public function update() { return response()->json(['message' => 'Not allowed'], 403); }
    public function destroy() { return response()->json(['message' => 'Not allowed'], 403); }
}