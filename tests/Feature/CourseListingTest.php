<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CourseListingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_is_not_allowed_for_guests()
    {
        $this->notAllowedToGuest('POST', route('course.add'));
    }

    /** @test */
    public function it_only_shows_active_courses_and_recent_on_top()
    {
        $user = User::factory()->create();

        Course::factory()->active()->count(5)->create();
        $this->travel(5)->minutes();
        $activeCourse = Course::factory()->active()->create();
        Course::factory()->inActive()->create();

        $this->actingAs($user)->json('GET', route('latest-courses.list'))
            ->assertJson(function (AssertableJson $json) use ($activeCourse) {
                $json
                    ->where('data.total', 6)
                    ->has('data.data.0', function ($json) use ($activeCourse) {
                        $json
                            ->where('id', $activeCourse->id)
                            ->etc();
                    })
                    ->etc();
            })
            ->assertStatus(200);
    }

    /** @test */
    public function it_loads_course_chapters_as_well()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        Chapter::factory()->count(5)->create(['course_id' => $course]);

        $this->actingAs($user)->json('GET', route('course.view', ['course' => $course]))
            ->assertJson(function (AssertableJson $json) {
                $json
                    ->has('data.chapters', 5)
                    ->etc();
            })
            ->assertStatus(200);
    }
}
