<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function store(Request $request)
    {
        $postData = $this->validate($request, [
            'name' => ['required', 'min:3'],
            'description' => ['required', 'min:5'],
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        $chapter = Chapter::create($postData);

        return response(['data' => $chapter], 201);
    }
}
