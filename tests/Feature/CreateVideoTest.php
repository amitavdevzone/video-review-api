<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CreateVideoTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private string $url;

    public function setUp(): void
    {
        parent::setUp();
        $this->url = 'https://youtube.com/watch?v=1sTux4ys3iE';
    }

    /** @test */
    public function it_creates_a_new_video(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->json('POST', route('video.add'), [
            'url' => $this->url,
            'description' => 'test',
        ]);

        $this->assertDatabaseHas('videos', [
            'url' => $this->url,
            'description' => 'test'
        ]);
    }

    /** @test */
    public function it_returns_video_in_response(): void
    {
        $user = User::factory()->create();

        $resp = $this->actingAs($user)->json('POST', route('video.add'), [
            'url' => $this->url,
        ]);

        $resp->assertJson(function (AssertableJson $json) {
            $json->where('id', 1)
                ->where('url', $this->url) // same url is coming
                ->where('type', 'youtube') // type is youtube
                ->etc();
        });
    }

    /** @test */
    public function it_returns_an_unpublished_video(): void
    {
        $url = $this->faker->url;
        $user = User::factory()->create();

        $resp = $this->actingAs($user)->json('POST', route('video.add'), [
            'url' => $this->url,
        ]);

        $resp->assertJson(function (AssertableJson $json) {
            $json->where('is_published', 0)
                ->etc();
        });
    }

    /** @test */
    public function it_adds_description_if_sent(): void
    {
        $user = User::factory()->create();

        $resp = $this->actingAs($user)->json('POST', route('video.add'), [
            'url' => $this->url,
            'description' => 'test',
        ]);

        $resp->assertJson(function (AssertableJson $json) {
            $json->where('description', 'test')
                ->etc();
        });
    }

    /** @test */
    public function it_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->json('POST', route('video.add'), [])
            ->assertStatus(422)
            ->assertJson(function (AssertableJson $json) {
                $json->has('errors.url')
                    ->etc();
            });
    }
}
