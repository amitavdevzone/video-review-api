<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RegistrationTest extends TestCase
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
    public function it_registers_a_user_with_verified_as_null()
    {
        $postData = $this->userRegistrationBaseData();

        $this->json('POST', route('user.register'), $postData);

        $this->assertDatabaseHas('users', [
            'email' => $postData['email'],
            'email_verified_at' => null
        ]);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $this->json('POST', route('user.register'), [])
            ->assertStatus(422)
            ->assertJson(function (AssertableJson $json) {
                $json->has('errors.email')
                    ->has('errors.password')
                    ->has('errors.confirm')
                    ->has('errors.name')
                    ->etc();
            });
    }

    /** @test */
    public function it_validates_passwords_are_same()
    {
        $postData = $this->userRegistrationBaseData();
        $postData['confirm'] = 'cpassword';

        $this->json('POST', route('user.register'), $postData)
            ->assertStatus(422)
            ->assertJson(function (AssertableJson $json) {
                $json->has('errors.confirm')
                    ->etc();
            });
    }

    /** @test */
    public function it_validates_unique_email()
    {
        $user = User::factory()->create();
        $postData = $this->userRegistrationBaseData();
        $postData['email'] = $user->email;

        $this->json('POST', route('user.register'), $postData)
            ->assertStatus(422)
            ->assertJson(function (AssertableJson $json) {
                $json->has('errors.email')
                    ->etc();
            });
    }

    public function userRegistrationBaseData(): array
    {
        return [
            'email' => $this->faker->email,
            'name' => $this->faker->name(),
            'password' => 'password',
            'confirm' => 'password',
        ];
    }
}
