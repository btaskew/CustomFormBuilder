<?php

namespace App\Mappers;

use App\Form;
use App\FormResponse;
use App\Question;

class ResponseMapper
{
    /**
     * @var array
     */
    private $responses;

    /**
     * @var Form
     */
    private $form;

    /**
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->responses = [];
        $this->form = $form;
    }

    public function map(): array
    {
        $this->form->responses->each(function ($response) {
            $this->mapQuestions($response);
        });

        return $this->responses;
    }

    /**
     * @param FormResponse $response
     * @return array
     */
    public function mapResponse(FormResponse $response): array
    {
        $this->mapQuestions($response);
        return $this->responses;
    }

    /**
     * @param FormResponse $response
     */
    private function mapQuestions(FormResponse $response): void
    {
        $this->form->getOrderedQuestions()->each(function ($question) use ($response) {
            $this->responses[$response->id]["id"] = $response->id;
            $this->responses[$response->id]["created_at"] = $response->created_at;
            $this->addResponse($question, $response);
        });
    }

    /**
     * @param Question     $question
     * @param FormResponse $response
     */
    private function addResponse(Question $question, FormResponse $response): void
    {
        if (isset($response->response->{$question->id})) {
            $this->responses[$response->id]["answers"][$question->title] = $response->response->{$question->id};
        } else {
            $this->responses[$response->id]["answers"][$question->title] = "n/a";
        }
    }
}