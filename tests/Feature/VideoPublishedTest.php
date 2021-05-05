<?php

namespace Tests\Feature;

use App\Events\VideoPublished;
use App\Mail\VideoPublishedEmailToOwner;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class VideoPublishedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_publishes_an_un_published_video()
    {
        $adminUser = User::factory()->admin()->create();

        $video = Video::factory()->unPublished()->create();

        $this->actingAs($adminUser)->json('POST', route('admin.video.publish'), [
            'id' => $video->id,
        ])->assertStatus(201)
            ->assertJson(function (AssertableJson $json) use ($video) {
                $json->where('is_published', 1)->etc();
            });
    }

    /** @test */
    public function it_fires_event_published_event()
    {
        Event::fake();

        $adminUser = User::factory()->admin()->create();

        $video = Video::factory()->unPublished()->create();

        $this->actingAs($adminUser)->json('POST', route('admin.video.publish'), [
            'id' => $video->id,
        ]);

        Event::assertDispatched(VideoPublished::class);
    }

    /** @test */
    public function it_sends_an_email_to_owner_when_published()
    {
        Mail::fake();

        $adminUser = User::factory()->admin()->create();
        $user = User::factory()->create();

        $video = Video::factory()->unPublished()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($adminUser)->json('POST', route('admin.video.publish'), [
            'id' => $video->id,
        ]);

        Mail::assertQueued(VideoPublishedEmailToOwner::class, function ($mail) use ($user) {
            return $mail->hasTo($user);
        });
    }
}
