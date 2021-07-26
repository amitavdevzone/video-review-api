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

    public function storeUpdate(Request $request)
    {
        $postData = $this->validate($request, [
            'course_id' => ['required', 'exists:courses,id'],
            'sequence' => ['required', 'array'],
        ]);

        $chapters = Chapter::query()
            ->where('course_id', $postData['course_id'])
            ->get();

        $sequence = collect($postData['sequence']);

        $sequence->each(function ($row) use ($chapters) {
            $chapter = $chapters->where('id', $row['id'])->first();
            $chapter->order = $row['weight'];
            $chapter->save();
        });

        return response('', 204);
    }
}
