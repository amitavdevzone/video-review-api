<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Response;

class AdminVideoController extends Controller
{
    public function unPublished(): Response
    {
        $videos = Video::query()
            ->unPublished()
            ->orderByDesc('created_at')
            ->paginate(10);

        return response($videos, 200);
    }
}
