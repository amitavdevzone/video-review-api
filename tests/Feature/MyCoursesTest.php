<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class MyCoursesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_is_not_allowed_for_guests()
    {
        $this->notAllowedToGuest('POST', route('course.add'));
    }

    /** @test */
    public function it_gives_published_and_unpublished_courses()
    {
        $user = User::factory()->create();
        Course::factory()->inActive()->create(['user_id' => $user->id]);
        Course::factory()->active()->create(['user_id' => $user->id]);

        $this->actingAs($user)->json('GET', route('courses.my-courses'))
            ->assertJson(function (AssertableJson $json) {
                $json
                    ->has('data.published', 1)
                    ->has('data.unpublished', 1)
                    ->etc();
            })
            ->assertStatus(200);
    }
}
