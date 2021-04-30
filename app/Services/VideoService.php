<?php

namespace App\Services;


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

    public function addVideoSubmission(array $postData, User $user): Model
    {
        $desc = '';

        if (isset($postData['description'])) {
            $desc = $postData['description'];
        }

        $video = Video::create([
            'url' => $postData['url'],
            'description' => $desc,
            'user_id' => $user->id,
            'type' => 'youtube',
            'is_published' => 0,
        ]);

        return $video;
    }
}
