<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::query()
            ->active()
            ->orderByDesc('created_at')
            ->paginate(20);

        return response(['data' => $courses], 200);
    }

    public function view(Course $course)
    {
        $course->load(['chapters']);
        return response(['data' => $course], 200);
    }

    public function store(Request $request)
    {
        $postData = $this->validate($request, [
            'name' => ['required', 'min:3'],
            'description' => ['required', 'min:5'],
        ]);

        $postData['user_id'] = $request->user()->id;

        $course = Course::create($postData);

        return response(['data' => $course], 201);
    }

    public function activate(Request $request)
    {
        $postData = $this->validate($request, [
            'ids' => ['required', 'array'],
        ]);

        $count = Course::where('user_id', $request->user()->id)
            ->whereIn('id', $postData['ids'])
            ->count();

        if ($count !== count($postData['ids'])) {
            throw ValidationException::withMessages([
                'ids' => ['There is problem with the course ids.']
            ]);
        }

        Course::whereIn('id', $postData['ids'])->update(['is_active' => 1]);

        return response('', 204);
    }

    public function myCourses()
    {
        $courses = Course::query()
            ->where('user_id', Auth::user()->id)
            ->get();

        $published = $courses->filter(function ($course) {
            return $course->is_active;
        });

        $unPublished = $courses->filter(function ($course) {
            return !$course->is_active;
        });

        return response(['data' => [
            'published' => $published,
            'unpublished' => $unPublished,
        ]], 200);
    }
}
