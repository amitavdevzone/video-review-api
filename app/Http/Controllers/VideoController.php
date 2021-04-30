<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Services\VideoService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    private VideoService $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }

    public function index(): Response
    {
        $videos = Video::query()
            ->published()
            ->orderByDesc('created_at')
            ->paginate(10);

        return response($videos, 200);
    }

    public function store(Request $request): Response
    {
        $postData = $this->validate($request, [
            'url' => ['required', 'url'],
            'description' => ['sometimes'],
        ]);

        $video = $this->videoService->addVideoSubmission($postData, Auth::user());

        return response($video, 201);
    }
}
