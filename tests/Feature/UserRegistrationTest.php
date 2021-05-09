<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_allows_a_user_to_register()
    {
        $postData = $this->userRegistrationBaseData();

        $this->json('POST', route('user.register'), $postData)
            ->assertStatus(201);
    }

    /** @test */
    public function it_registers_a_user_with_verified_at_as_null()
    {
        $postData = $this->userRegistrationBaseData();

        $this->json('POST', route('user.register'), $postData);


        $this->assertDatabaseHas('users', [
            'email' => $postData['email'],
            'email_verified_at' => null,
        ]);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $this->json('POST', route('user.register'), [])
            ->assertJson(function (AssertableJson $json) {
                $json->has('errors')
                    ->has('errors.email')
                    ->has('errors.name')
                    ->has('errors.password')
                    ->has('errors.confirm')
                    ->etc();
            });
    }

    /** @test */
    public function it_requires_unique_email()
    {
        $user = User::factory()->create();

        $defaultUser = $this->userRegistrationBaseData();
        $defaultUser['email'] = $user->email;

        $this->json('POST', route('user.register'), $defaultUser)
            ->assertJson(function (AssertableJson $json) {
                $json->has('errors.email')
                    ->etc();
            });
    }

    private function userRegistrationBaseData(): array
    {
        return [
            'email' => $this->faker->email,
            'password' => 'password',
            'confirm' => 'password',
            'name' => $this->faker->name(),
        ];
    }
}
