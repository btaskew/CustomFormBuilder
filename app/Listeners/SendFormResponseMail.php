<?php

namespace App\Listeners;

use App\Contracts\FormResponseMailMapper;
use App\Events\ResponseRecorded;
use App\FormResponse;
use App\Mail\FormResponse as FormResponseMail;
use App\Specifications\CanSendResponseEmail;
use Illuminate\Support\Facades\Mail;

class SendFormResponseMail
{
    /**
     * @var FormResponseMailMapper
     */
    private $mailMapper;

    /**
     * @param FormResponseMailMapper $mailMapper
     */
    public function __construct(FormResponseMailMapper $mailMapper)
    {
        $this->mailMapper = $mailMapper;
    }

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
                new FormResponseMail($this->mailMapper->mapResponse($event->form->response_email, $event->response))
            );
    }

    /**
     * @param int          $questionId
     * @param FormResponse $response
     * @return string
     */
    private function setMailTo(int $questionId, FormResponse $response)
    {
        return $response->response[$questionId];
    }
}
