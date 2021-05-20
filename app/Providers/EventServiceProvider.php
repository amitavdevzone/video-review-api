<?php

namespace App\Providers;

use App\Events\UserRegistered;
use App\Events\VideoPublished;
use App\Events\VideoSubmitted;
use App\Listeners\UserRegistrationHandler;
use App\Listeners\VideoPublishedOwnerEmail;
use App\Listeners\VideoSubmittedHandler;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        VideoPublished::class => [
            VideoPublishedOwnerEmail::class
        ],
        UserRegistered::class => [
            UserRegistrationHandler::class,
        ],
        VideoSubmitted::class => [
            VideoSubmittedHandler::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
