<?php

namespace App\Events;

use App\Form;
use App\FormResponse;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResponseRecorded
{
    use Dispatchable, SerializesModels;

    /**
     * @var FormResponse
     */
    public $response;

    /**
     * @var Form
     */
    public $form;

    /**
     * Create a new event instance.
     *
     * @param FormResponse $response
     */
    public function __construct(FormResponse $response)
    {
        $this->response = $response;
        $this->form = $response->form;
    }
}
