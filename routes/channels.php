<?php

use App\Models\Booking;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('bookings.{bookingId}', function ($user, $bookingId) {
    $booking = Booking::find($bookingId);
    return $booking && ($user->id === $booking->client_id || $user->id === $booking->freelancer_id);
});
