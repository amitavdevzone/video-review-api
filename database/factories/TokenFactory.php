<?php

namespace Database\Factories;

use App\Models\Token;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TokenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Token::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'token' => Str::uuid(),
            'user_id' => User::factory()->unverified()->create(),
            'expires_at' => now()->addMinutes(config('app.expires_at_in_min'))
        ];
    }
}
