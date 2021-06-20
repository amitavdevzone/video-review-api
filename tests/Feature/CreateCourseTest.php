<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CreateCourseTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_is_not_allowed_for_guests()
    {
        $this->notAllowedToGuest('POST', route('course.add'));
    }

    /** @test */
    public function it_needs_basic_field_info()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->json('POST', route('course.add'), [])
            ->assertJson(function (AssertableJson $json) {
                $json
                    ->has('errors.name')
                    ->has('errors.description')
                    ->etc();
            })
            ->assertStatus(422);
    }

    /** @test */
    public function it_creates_an_entry_to_database()
    {
        $user = User::factory()->create();

        $postData = [
            'name' => 'My first course',
            'description' => $this->faker->sentence(),
        ];

        $this->actingAs($user)->json('POST', route('course.add'), $postData);

        $this->assertDatabaseHas('courses', [
            'name' => $postData['name'],
        ]);
    }

    /** @test */
    public function it_sends_proper_status_code()
    {
        $user = User::factory()->create();

        $postData = [
            'name' => 'My first course',
            'description' => $this->faker->sentence(),
        ];

        $this->actingAs($user)->json('POST', route('course.add'), $postData)
            ->assertStatus(201);
    }

    /** @test */
    public function it_adds_correct_user_id()
    {
        $user = User::factory()->create();

        $postData = [
            'name' => 'My first course',
            'description' => $this->faker->sentence(),
        ];

        $this->actingAs($user)->json('POST', route('course.add'), $postData)
            ->assertJson(function (AssertableJson $json) use ($user) {
                $json
                    ->where('data.user_id', $user->id)
                    ->etc();
            });
    }
}
