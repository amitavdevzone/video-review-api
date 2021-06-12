<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LikeController extends Controller
{
    public function store(Request $request)
    {
        $likeEntities = config('app.like_entities');

        $postData = $this->validate($request, [
            'entity' => ['required', Rule::in($likeEntities)],
            'entity_id' => ['required'],
        ]);

        if ($postData['entity'] === 'video') {
            $count = Video::where('id', $postData['entity_id'])->count();
        } else {
            $count = Comment::where('id', $postData['entity_id'])->count();
        }

        if ($count === 0) {
            throw ValidationException::withMessages([
                'entity_id' => ['Entity not found'],
            ]);
        }

        $like = Like::create([
            'entity' => $postData['entity'],
            'entity_id' => $postData['entity_id'],
            'user_id' => Auth::user()->id,
        ]);

        return response(['data' => $like], 201);
    }
}
