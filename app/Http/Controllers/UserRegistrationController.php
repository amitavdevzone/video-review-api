<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TokenService;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserRegistrationController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(Request $request)
    {
        $postData = $this->validate($request, [
            'email' => ['required', 'unique:users,email', 'email'],
            'password' => ['required', 'min:3'],
            'confirm' => ['required', 'same:password'],
            'name' => ['required'],
        ]);

        $user = $this->userService->createUser($postData);

        return response(['user' => $user], 201);
    }
}
