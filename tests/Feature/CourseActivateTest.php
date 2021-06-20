<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class CourseActivateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_is_not_allowed_for_guests()
    {
        $this->notAllowedToGuest('POST', route('course.add'));
    }

    /** @test */
    public function it_accepts_an_array_of_ids()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->json('POST', route('course.activate'), ['ids' => 1])
            ->assertJson(function (AssertableJson $json) {
                $json
                    ->has('errors.ids')
                    ->etc();
            })
            ->assertStatus(422);
    }

    /** @test */
    public function it_validates_that_course_ids_are_present()
    {
        $user = User::factory()->create();

        Course::factory()->active()->count(5)->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)->json('POST', route('course.activate'), [
            'ids' => [1, 2, 7],
        ])->assertJson(function (AssertableJson $json) {
            $json
                ->has('errors.ids')
                ->etc();
        })->assertStatus(422);
    }

    /** @test */
    public function it_does_not_accept_ids_for_other_user_courses()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $course2 = Course::factory()->active()->create(['user_id' => $user2->id]);

        $this->actingAs($user1)->json('POST', route('course.activate'), [
            'ids' => [$course2->id],
        ])->assertJson(function (AssertableJson $json) {
            $json
                ->has('errors.ids')
                ->etc();
        })->assertStatus(422);
    }

    /** @test */
    public function it_actives_the_courses()
    {
        $user = User::factory()->create();

        Course::factory()->inActive()->count(5)->create([
            'user_id' => $user->id,
        ]);

        $ids = [1, 2];
        $this->actingAs($user)->json('POST', route('course.activate'), [
            'ids' => $ids,
        ])->assertStatus(204);

        foreach ($ids as $id) {
            $this->assertDatabaseHas('courses', [
                'id' => $id,
                'is_active' => 1,
            ]);
        }
    }
}
