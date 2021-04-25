<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CreateVideoTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_creates_a_new_video(): void
    {
        $url = $this->faker->url;

        $this->json('POST', route('video.add'), [
            'url' => $url,
            'description' => 'test',
        ]);

        $this->assertDatabaseHas('videos', [
            'url' => $url,
            'description' => 'test'
        ]);
    }

    /** @test */
    public function it_returns_video_in_response(): void
    {
        $url = $this->faker->url;

        $resp = $this->json('POST', route('video.add'), [
            'url' => $url,
        ]);

        $resp->assertJson(function (AssertableJson $json) use ($url) {
            $json->where('id', 1)
                ->where('url', $url) // same url is coming
                ->where('type', 'youtube') // type is youtube
                ->etc();
        });
    }

    /** @test */
    public function it_returns_an_unpublished_video(): void
    {
        $url = $this->faker->url;

        $resp = $this->json('POST', route('video.add'), [
            'url' => $url,
        ]);

        $resp->assertJson(function (AssertableJson $json) use ($url) {
            $json->where('is_published', 0)
                ->etc();
        });
    }

    /** @test */
    public function it_adds_description_if_sent(): void
    {
        $url = $this->faker->url;

        $resp = $this->json('POST', route('video.add'), [
            'url' => $url,
            'description' => 'test',
        ]);

        $resp->assertJson(function (AssertableJson $json) use ($url) {
            $json->where('description', 'test')
                ->etc();
        });
    }

    /** @test */
    public function it_validates_required_fields(): void
    {
        $this->json('POST', route('video.add'), [])
            ->assertStatus(422)
            ->assertJson(function (AssertableJson $json) {
                $json->has('errors.url')
                    ->etc();
            });
    }
}
