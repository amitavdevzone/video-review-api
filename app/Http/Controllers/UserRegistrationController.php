<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TokenService;
use Illuminate\Http\Request;

class UserRegistrationController extends Controller
{
    private $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function __invoke(Request $request)
    {
        $postData = $this->validate($request, [
            'email' => ['required', 'unique:users,email', 'email'],
            'password' => ['required', 'min:3'],
            'confirm' => ['required', 'same:password'],
            'name' => ['required'],
        ]);

        unset($postData['confirm']);
        $postData['password'] = bcrypt($postData['password']);
        $postData['email_verified_at'] = null;

        $user = User::create($postData);

        $this->tokenService->createToken($user);

        return response(['user' => $user], 201);
    }
}
