<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AdminUserListingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_is_accessable_by_admin_only()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->json('GET', route('admin.user.list'))
            ->assertStatus(401);
    }

    /** @test */
    public function it_gives_a_paginated_data()
    {
        $user = User::factory()->admin()->create();
        User::factory()->count(5)->create();

        $this->actingAs($user)->json('GET', route('admin.user.list'))
            ->assertJson(function (AssertableJson $json) {
                $json
                    ->has('data.current_page')
                    ->has('data.data', 6)
                    ->where('data.total', 6)
                    ->etc();
            })
            ->assertStatus(200);
    }
}
