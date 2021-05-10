<?php

namespace App\Services;


use App\Models\Token;
use App\Models\User;
use Illuminate\Support\Str;

class TokenService
{
    public function createToken(User  $user): Token
    {
        return Token::create([
            'token' => Str::uuid(),
            'user_id' => $user->id,
            'expires_at' => now()->addMinutes(config('app.expires_at_in_min')),
        ]);
    }
}
