<?php

namespace App\Mail;

use App\Models\Token;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserVerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $user;

    private $token;

    public function __construct(User $user, Token $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function build()
    {
        $url = config('app.front_end_app_url') . "user/verify/{$this->token->token}";
        return $this->subject('Verify your email on Video Review')
            ->markdown('emails.user.verify')
            ->with('url', $url)
            ->with('user', $this->user);
    }
}
