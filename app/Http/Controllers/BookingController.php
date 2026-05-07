<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $bookings = Booking::with(['service', 'client:id,name', 'freelancer:id,name'])
            ->where('client_id', $user->id)
            ->orWhere('freelancer_id', $user->id)
            ->latest()
            ->get();

        return Inertia::render('Bookings/Index', [
            'bookings' => $bookings
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'note' => 'nullable|string|max:500',
        ]);

        $service = Service::findOrFail($validated['service_id']);

        $booking = Booking::create([
            'client_id' => auth()->id(),
            'freelancer_id' => $service->freelancer_id,
            'service_id' => $service->id,
            'booking_date' => $validated['booking_date'],
            'note' => $validated['note'] ?? '',
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.show', $booking)->with('success', 'Booking berhasil dibuat! Menunggu konfirmasi freelancer.');
    }

    public function show(Booking $booking)
    {
        if ($booking->client_id !== auth()->id() && $booking->freelancer_id !== auth()->id()) {
            abort(403);
        }

        return Inertia::render('Bookings/Show', [
            'booking' => $booking->load(['service', 'client:id,name,email', 'freelancer:id,name,email', 'messages.sender:id,name'])
        ]);
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        if ($booking->freelancer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected,done'
        ]);

        $booking->update(['status' => $validated['status']]);

        return back()->with('success', "Status booking diperbarui menjadi {$validated['status']}.");
    }
}
