<?php

namespace App\Listeners;

use App\Events\ResponseRecorded;
use App\FormResponse;
use App\Specifications\CanSendResponseEmail;
use Illuminate\Support\Facades\Mail;

class SendFormResponseMail
{
    /**
     * @param ResponseRecorded $event
     */
    public function handle(ResponseRecorded $event)
    {
        if (!CanSendResponseEmail::isSatisfiedBy($event->form, $event->response)) {
            return;
        }

        Mail::to($this->setMailTo($event->form->response_email_field, $event->response))
            ->send(
                new \App\Mail\FormResponse($event->form->response_email)
            );
    }

    /**
     * @param int          $questionId
     * @param FormResponse $response
     * @return string
     */
    private function setMailTo(int $questionId, FormResponse $response)
    {
        return $response->response->{$questionId};
    }
}