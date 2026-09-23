<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;
    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message->load('sender', 'chat');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $chat = $this->message->chat;

        return [
            new PrivateChannel('chat.' . $chat->user_id),
            new PrivateChannel('chat.' . $chat->sp_id),
        ];
    }
    public function broadcastAs(): string
    {
        return 'MessageSent';
    }
}
