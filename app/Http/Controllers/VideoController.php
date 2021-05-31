<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Rules\YoutubeUrlRule;
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
            'url' => ['required', 'url', new YoutubeUrlRule],
            'description' => ['sometimes'],
            'title' => ['required'],
        ]);

        $video = $this->videoService->addVideoSubmission($postData, Auth::user());

        return response($video, 201);
    }

    public function view(Video $video)
    {
        $video->load(['comments', 'comments.user']);

        if ($video->is_published != 1) {
            abort(404, 'Video is not published.');
        }

        return response($video, 200);
    }
}
