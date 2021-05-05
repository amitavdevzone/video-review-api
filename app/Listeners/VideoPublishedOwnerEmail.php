<?php

namespace App\Listeners;

use App\Events\VideoPublished;
use App\Mail\VideoPublishedEmailToOwner;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class VideoPublishedOwnerEmail implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(VideoPublished $event)
    {
        $user = User::find($event->video->user_id);

        Mail::to($user)
            ->queue(new VideoPublishedEmailToOwner($event->video));
    }
}
