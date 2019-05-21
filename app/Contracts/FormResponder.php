<?php

namespace App\Contracts;

use App\Form;
use App\Http\Requests\ResponseRequest;

interface FormResponder
{
    /**
     * @param ResponseRequest $response
     * @param Form            $form
     */
    public function saveResponse(ResponseRequest $response, Form $form): void;
}
