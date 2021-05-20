<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Services\VideoService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminVideoController extends Controller
{
    private $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }

    public function unPublished(): Response
    {
        $videos = Video::query()
            ->unPublished()
            ->orderByDesc('created_at')
            ->paginate(10);

        return response($videos, 200);
    }

    public function publish(Request $request): Response
    {
        $postData = $this->validate($request, [
            'id' => ['required', 'exists:videos,id'],
        ]);

        $video = $this->videoService->publishVideoById($postData['id']);

        return response($video, 201);
    }
}
