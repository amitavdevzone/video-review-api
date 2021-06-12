<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LikeEntityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_needs_required_fields()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->json('POST', route('like.entity'), [])
            ->assertJson(function (AssertableJson $json) {
                $json
                    ->has('errors.entity')
                    ->has('errors.entity_id')
                    ->etc();
            })
            ->assertStatus(422);
    }

    /** @test */
    public function it_validates_if_entity_id_is_not_present()
    {
        $user = User::factory()->create();

        $postData = [
            'entity' => 'video',
            'entity_id' => 1,
        ];

        $this->actingAs($user)->json('POST', route('like.entity'), $postData)
            ->assertJson(function (AssertableJson $json) {
                $json
                    ->has('errors.entity_id')
                    ->etc();
            })
            ->assertStatus(422);
    }

    /** @test */
    public function it_adds_like_when_video_is_present()
    {
        $user = User::factory()->create();
        $video = Video::factory()->create();

        $postData = [
            'entity' => 'video',
            'entity_id' => $video->id,
        ];

        $this->actingAs($user)->json('POST', route('like.entity'), $postData)
            ->assertJson(function (AssertableJson $json) use ($video) {
                $json
                    ->has('data')
                    ->where('data.entity', 'video')
                    ->where('data.entity_id', $video->id)
                    ->etc();
            })
            ->assertStatus(201);
    }

    /** @test */
    public function it_adds_like_when_comment_is_present()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $postData = [
            'entity' => 'comment',
            'entity_id' => $comment->id,
        ];

        $this->actingAs($user)->json('POST', route('like.entity'), $postData)
            ->assertJson(function (AssertableJson $json) use ($comment) {
                $json
                    ->has('data')
                    ->where('data.entity', 'comment')
                    ->where('data.entity_id', $comment->id)
                    ->etc();
            })
            ->assertStatus(201);
    }
}
