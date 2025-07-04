<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Comment;
use App\Models\User;

class CommentSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

     public $comment;
    /**
     * Create a new event instance.
     */
    public function __construct(Comment $comment)
    {
         $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
   public function broadcastOn(): array
{
    return [new Channel('post.' . $this->comment->post_id)];
}


    public function broadcastAs()
    {
        return 'comment.sent';
    }

    public function broadcastWith()
{
    return [
        'comment' => $this->comment->load('user')
    ];
}
}
