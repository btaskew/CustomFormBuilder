<?php

namespace App\Listeners;

use App\Contracts\ResponseFormatter;
use App\Events\ResponseRecorded;
use App\Mail\FormResponded;
use Illuminate\Support\Facades\Mail;

class SendFormRespondedNotification
{
    /**
     * @var ResponseFormatter
     */
    private $responseFormatter;

    /**
     * @param ResponseFormatter $responseFormatter
     */
    public function __construct(ResponseFormatter $responseFormatter)
    {
        $this->responseFormatter = $responseFormatter;
    }

    /**
     * @param ResponseRecorded $event
     * @return void
     */
    public function handle(ResponseRecorded $event)
    {
        if (is_null($event->form->admin_email)) {
            return;
        }

        Mail::to($this->setMailTo($event->form->admin_email))->send(
            new FormResponded($event->form, $event->response, $this->responseFormatter)
        );
    }

    /**
     * @param string $adminEmails
     * @return array
     */
    private function setMailTo(string $adminEmails): array
    {
        $emails = [];

        foreach (explode(';', $adminEmails) as $email) {
            filter_var($email, FILTER_VALIDATE_EMAIL) && $emails[] = $email;
        }

        return $emails;
    }
}
