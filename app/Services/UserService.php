<?php

namespace App\Services;


use App\Events\UserRegistered;
use App\Models\User;

class UserService
{
    public function createUser(array $postData)
    {
        unset($postData['confirm']);
        $postData['password'] = bcrypt($postData['password']);
        $postData['email_verified_at'] = null;

        $user = User::create($postData);

        event(new UserRegistered($user));

        return $user;
    }
}
