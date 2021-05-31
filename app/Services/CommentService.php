<?php

namespace App\Services;

use App\Models\User;
use App\Models\Video;

class CommentService
{
    public function addCommentToVideo(User $user, array $data)
    {
        $video = Video::findorFail($data['video_id']);

        $comment = $video->comments()->create([
            'comment' => $data['comment'],
            'user_id' => $user->id,
        ]);

        return $comment->load(['user']);
    }
}
