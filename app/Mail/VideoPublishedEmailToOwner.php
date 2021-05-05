<?php

namespace App\Mail;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VideoPublishedEmailToOwner extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private Video $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function build(): VideoPublishedEmailToOwner
    {
        return $this->subject('Video published')
            ->markdown('emails.videos.publish-email-to-owner');
    }
}
