<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;

class TokenController extends Controller
{
    public function __invoke(Token $token)
    {
        if (Carbon::parse($token->expires_at)->isPast()) {
            return response('Token expired', 400);
        }

        $user = User::find($token->user_id);
        $user->email_verified_at = now();
        $user->save();

        $jwt = $user->createToken('web_app')->plainTextToken;

        $token->delete();

        return response([
            'token' => $jwt,
            'user_name' => $user->name,
        ], 200);
    }
}
