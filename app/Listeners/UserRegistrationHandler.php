<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\UserVerificationEmail;
use App\Services\TokenService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class UserRegistrationHandler implements ShouldQueue
{
    private $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function handle(UserRegistered $event)
    {
        $user = $event->user;

        $token = $this->tokenService->createToken($user);

        Mail::to($user)->queue(new UserVerificationEmail($user, $token));
    }
}
