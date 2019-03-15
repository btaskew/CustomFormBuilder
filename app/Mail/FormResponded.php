<?php

namespace App\Mail;

use App\Form;
use App\FormResponse;
use App\Services\ResponseFormatter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormResponded extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Form
     */
    private $form;

    /**
     * @var FormResponse
     */
    private $response;

    /**
     * Create a new message instance.
     *
     * @param Form         $form
     * @param FormResponse $response
     */
    public function __construct(Form $form, FormResponse $response)
    {
        $this->form = $form;
        $this->response = $response;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->form->title . " - response recorded")
            ->from('no-reply@exeter.ac.uk')
            ->view('emails.formResponded', [
                'response' => $this->getResponse(),
                'form' => $this->form
            ]);
    }

    /**
     * @return array
     */
    private function getResponse(): array
    {
        return (new ResponseFormatter($this->form->getAnswerableQuestions()))
            ->mapResponse($this->response)[$this->response->id];
    }
}
