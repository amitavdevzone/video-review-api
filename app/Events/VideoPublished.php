<?php

namespace App\Events;

use App\Models\Video;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoPublished
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Video $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
