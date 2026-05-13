<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message->load('sender:id,name,avatar');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        $this->message->loadMissing('booking');
        $booking = $this->message->booking;
        
        $recipientId = $this->message->sender_id === $booking->client_id 
            ? $booking->freelancer_id 
            : $booking->client_id;

        return [
            new PrivateChannel('bookings.' . $this->message->booking_id),
            new PrivateChannel('App.Models.User.' . $recipientId),
        ];
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
}
