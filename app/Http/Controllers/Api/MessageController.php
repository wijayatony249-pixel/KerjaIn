<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Booking $booking)
    {
        return response()->json(
            Message::with('sender:id,name,avatar')
                ->where('booking_id', $booking->id)
                ->oldest()->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'message'    => 'required|string',
        ]);

        $message = Message::create([
            'booking_id' => $request->booking_id,
            'sender_id'  => auth()->id(),
            'message'    => $request->message,
        ]);

        return response()->json($message->load('sender:id,name,avatar'), 201);
    }
}