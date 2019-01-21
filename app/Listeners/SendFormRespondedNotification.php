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

        Mail::to($this->setMailTo($event->form->admin_email))->send(
            new FormResponded($event->form, $event->response)
        );
    }

    /**
     * @param string $adminEmails
     * @return array
     */
    private function setMailTo(string $adminEmails): array
    {
        $emails = [];

        foreach (explode(";", $adminEmails) as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emails[] = $email;
            }
        }

        return $emails;
    }
}
