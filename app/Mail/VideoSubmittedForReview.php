<?php

namespace App\Mail;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VideoSubmittedForReview extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function build()
    {
        return $this->subject('Video submitted for review')
            ->markdown('emails.video.video-submit')
            ->with('video', $this->video);
    }
}
