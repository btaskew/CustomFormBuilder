<?php

namespace App\Listeners;

use App\Events\ResponseRecorded;
use App\Mail\FormResponded;
use Illuminate\Support\Facades\Mail;

class SendFormRespondedNotification
{
    /**
     * Handle the event.
     *
     * @param ResponseRecorded $event
     * @return void
     */
    public function handle(ResponseRecorded $event)
    {
        if (is_null($event->form->admin_email)) {
            return;
        }

        Mail::to($event->form->admin_email)->send(
            new FormResponded($event->form, $event->response)
        );
    }
}
