<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class VideoViewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_allows_only_authenticated_users_only()
    {
        $video = Video::factory()->create();
        $this->json('GET', route('video.view', ['video' => $video]))
            ->assertStatus(401);
    }

    /** @test */
    public function it_shows_exception_if_not_published()
    {
        $video = Video::factory()->unPublished()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->json('GET', route('video.view', ['video' => $video]))
            ->assertStatus(404);
    }

    /** @test */
    public function it_shows_video_data()
    {
        $video = Video::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->json('GET', route('video.view', ['video' => $video]))
            ->assertJson(function (AssertableJson $json) use ($video) {
                $json->where('title', $video->title)
                    ->etc();
            });
    }
}
