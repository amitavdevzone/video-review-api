<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CommentAddTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_needs_authenticated_user()
    {
        $this->notAllowedToGuest('POST', route('comment.save'));
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->json('POST', route('comment.save'), [])
            ->assertJson(function(AssertableJson $json) {
                $json
                    ->has('errors')
                    ->has('errors.video_id')
                    ->has('errors.comment')
                    ->etc();
            })
            ->assertStatus(422);
    }

    /** @test */
    public function it_adds_a_new_comment()
    {
        $user = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $user->id]);
        $postData = [
            'video_id' => $video->id,
            'comment' => 'This is my comment',
        ];

        $this->actingAs($user)->json('POST', route('comment.save'), $postData)
            ->assertJson(function(AssertableJson $json) use ($postData) {
                $json
                    ->has('data')
                    ->has('data.user')
                    ->where('data.comment', $postData['comment'])
                    ->etc();
            })->assertStatus(201);
    }
}
