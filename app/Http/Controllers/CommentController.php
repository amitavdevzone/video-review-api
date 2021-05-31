<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentAddRequest;
use App\Models\Video;
use App\Services\CommentService;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(CommentAddRequest $request, CommentService $commentService)
    {
        $postData = $request->validated();

        $comment = $commentService->addCommentToVideo(Auth::user(), $postData);

        return response(['data' => $comment], 201);
    }
}
