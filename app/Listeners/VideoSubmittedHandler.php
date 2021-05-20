<?php

namespace App\Listeners;

use App\Events\VideoSubmitted;
use App\Mail\VideoSubmittedForReview;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class VideoSubmittedHandler
{
    public function __construct()
    {
        //
    }

    public function handle(VideoSubmitted $event)
    {
        Mail::to(User::find(1))
            ->queue(new VideoSubmittedForReview($event->video));
    }
}
