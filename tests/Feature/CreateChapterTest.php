<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CreateChapterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_is_not_allowed_for_guests()
    {
        $this->notAllowedToGuest('POST', route('course.add'));
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->json('POST', route('chapter.save'), [])
            ->assertJson(function (AssertableJson $json) {
                $json
                    ->has('errors.name')
                    ->has('errors.description')
                    ->has('errors.course_id')
                    ->etc();
            })
            ->assertStatus(422);
    }

    /** @test */
    public function it_creates_an_entry()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();

        $postData = [
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(),
            'course_id' => $course->id,
        ];

        $this->actingAs($user)->json('POST', route('chapter.save'), $postData)
            ->assertStatus(201);

        $this->assertDatabaseHas('chapters', [
            'name' => $postData['name'],
            'course_id' => $course->id,
        ]);
    }
}
