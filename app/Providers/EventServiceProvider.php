<?php

namespace App\Providers;

use App\Events\ResponseRecorded;
use App\Listeners\SendFormRespondedNotification;
use App\Listeners\SendFormResponseMail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ResponseRecorded::class => [
            SendFormRespondedNotification::class,
            SendFormResponseMail::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
