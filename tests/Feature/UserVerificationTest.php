<?php

namespace Tests\Feature;

use App\Models\Token;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_verifies_a_user_when_token_is_correct()
    {
        $token = Token::factory()->create();

        $this->travel(5)->minutes();

        $this->json('GET', route('user.verify', ['token' => $token->token]))
            ->assertStatus(200)->assertJson(function (AssertableJson $json) {
                $json
                    ->has('token')
                    ->has('user_name')
                    ->etc();
            });
    }

    /** @test */
    public function it_updates_the_email_verified_at_field()
    {
        $token = Token::factory()->create();

        $this->travel(5)->minutes();

        $this->json('GET', route('user.verify', ['token' => $token->token]));

        $this->assertNotNull(User::find($token->user_id)->email_verified_at);
    }

    /** @test */
    public function it_throws_error_when_token_is_expired()
    {
        $token = Token::factory()->create();

        $this->travel(50)->minutes();

        $this->json('GET', route('user.verify', ['token' => $token->token]))
            ->assertStatus(400);
    }
}
