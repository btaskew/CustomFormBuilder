<?php

namespace App\Mail;

use App\Contracts\ResponseFormatter;
use App\Form;
use App\FormResponse;
use App\Objects\FormattedResponse;
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
     * @var ResponseFormatter
     */
    private $responseFormatter;

    /**
     * @param Form              $form
     * @param FormResponse      $response
     * @param ResponseFormatter $responseFormatter
     */
    public function __construct(Form $form, FormResponse $response, ResponseFormatter $responseFormatter)
    {
        $this->form = $form;
        $this->response = $response;
        $this->responseFormatter = $responseFormatter;
    }

    /**
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->form->title . ' - response recorded')
            ->from('no-reply@exeter.ac.uk')
            ->view('emails.formResponded', [
                'response' => $this->getResponse(),
                'form' => $this->form
            ]);
    }

    /**
     * @return FormattedResponse
     */
    private function getResponse(): FormattedResponse
    {
        return $this->responseFormatter
            ->setQuestions($this->form->getAnswerableQuestions())
            ->formatResponses([$this->response])[$this->response->id];
    }
}
