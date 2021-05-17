<?php

namespace App\Services;


use App\Events\VideoPublished;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Model;

class VideoService
{
    public function validateYoutubeUrl(string $url): bool
    {
        $youtubeRegexp = "/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+/";

        if (preg_match($youtubeRegexp, $url) == 1) {
            return true;
        }

        return false;
    }

    public function youtubeThumbnail(string $url): string
    {
        if (strpos($url, 'yout')) {
            preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);
            return $matches[0];
        }
        return "";
    }

    public function addVideoSubmission(array $postData, User $user): Model
    {
        $desc = '';

        if (isset($postData['description'])) {
            $desc = $postData['description'];
        }

        $video = Video::create([
            'url' => $postData['url'],
            'description' => $desc,
            'title' => $postData['title'],
            'user_id' => $user->id,
            'type' => 'youtube',
            'is_published' => 0,
        ]);

        return $video;
    }

    public function publishVideoById(int $id)
    {
        $video = Video::find($id);
        $video->is_published = 1;
        $video->save();

        event(new VideoPublished($video));

        return $video;
    }
}
