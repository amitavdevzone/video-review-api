<?php

namespace Tests\Unit;

use App\Services\VideoService;
use PHPUnit\Framework\TestCase;

class YoutubeUrlTest extends TestCase
{
    /** @test */
    public function it_validates_correct_youtube_urls()
    {
        $videoService = app()->make(VideoService::class);

        $urls = [
            'https://www.youtube.com/watch?v=1sTux4ys3iE&ab_channel=AmitavRoy',
            'https://youtu.be/1sTux4ys3iE?t=20',
            'https://youtube.com/watch?v=1sTux4ys3iE',
            'https://youtu.be/1sTux4ys3iE',
        ];

        foreach ($urls as $url) {
            $this->assertTrue($videoService->validateYoutubeUrl($url));
        }
    }

    /** @test */
    public function it_validates_wrong_urls()
    {
        $videoService = app()->make(VideoService::class);

        $urls = [
            'you.be/1sTux4ys3iE',
            'htpps://vimeo.com/v=1236'
        ];

        foreach ($urls as $url) {
            $this->assertFalse($videoService->validateYoutubeUrl($url));
        }
    }
}
