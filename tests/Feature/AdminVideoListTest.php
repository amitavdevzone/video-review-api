<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AdminVideoListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_allows_admins_only()
    {
        $this->checkAllowedToAdmin();
    }

    /** @test */
    public function it_shows_unpublished_videos()
    {
        Video::factory()->unPublished()->count(2)->create();
        Video::factory()->count(2)->create();
        $adminUser = User::factory()->admin()->create();

        $this->actingAs($adminUser)
            ->json('GET', route('admin.video.list'))
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', 2)
                    ->where('data.0.is_published', '0')
                    ->etc();
            });
    }
}
