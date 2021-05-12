<?php

namespace Tests\Feature;

use App\Events\UserRegistered;
use App\Mail\UserVerificationEmail;
use App\Models\Token;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\
Facades\Mail;
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

    /** @test */
    public function it_adds_a_token_for_verification()
    {
        $postData = $this->userRegistrationBaseData();

        $resp = $this->json('POST', route('user.register'), $postData);

        $data = json_decode($resp->getContent(), true);

        $this->assertDatabaseHas('tokens', [
            'user_id' => $data['user']['id'],
            'expires_at' => now()->addMinutes(config('app.expires_at_in_min'))
        ]);
    }

    /** @test */
    public function it_fires_user_register_event()
    {
        Event::fake();

        $postData = $this->userRegistrationBaseData();

        $this->json('POST', route('user.register'), $postData);

        Event::assertDispatched(UserRegistered::class);
    }

    /** @test */
    public function it_sends_the_verification_email()
    {
        Mail::fake();

        $postData = $this->userRegistrationBaseData();

        $this->json('POST', route('user.register'), $postData);

        Mail::assertQueued(UserVerificationEmail::class);
    }

    /** @test */
    public function it_sends_email_to_registered_user()
    {
        Mail::fake();

        $postData = $this->userRegistrationBaseData();

        $this->json('POST', route('user.register'), $postData);

        Mail::assertQueued(UserVerificationEmail::class, function ($mail) use ($postData) {
            $user = User::where('email', $postData['email'])->first();
            return $mail->hasTo($user);
        });
    }

    /** @test */
    public function it_sends_the_token()
    {
        $user = User::factory()->unverified()->create();
        $token = Token::factory()->create(['user_id' => $user->id]);

        $mailable = new UserVerificationEmail($user, $token);
        $mailable->assertSeeInHtml($token->token);
    }

    /** @test */
    public function it_shows_the_user_name()
    {
        $user = User::factory()->unverified()->create();
        $token = Token::factory()->create(['user_id' => $user->id]);

        $mailable = new UserVerificationEmail($user, $token);
        $mailable->assertSeeInHtml($user->name);
    }

    private function userRegistrationBaseData(): array
    {
        return [
            'email' => $this->faker->email,
            'name' => $this->faker->name(),
            'password' => 'password',
            'confirm' => 'password',
        ];
    }
}
