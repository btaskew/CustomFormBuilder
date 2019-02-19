<?php

namespace App\Mappers;

use App\Form;
use App\FormResponse;
use App\Question;
use Illuminate\Support\Collection;

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
     * @var Collection
     */
    private $questions;

    /**
     * @param Form       $form
     * @param Collection $questions
     */
    public function __construct(Form $form, Collection $questions)
    {
        $this->responses = [];
        $this->form = $form;
        $this->questions = $questions;
    }

    /**
     * @param Collection $responses
     * @return array
     */
    public function map(Collection $responses): array
    {
        $responses->each(function ($response) {
            $this->mapResponse($response);
        });

        return $this->responses;
    }

    /**
     * @param FormResponse $response
     * @return array
     */
    public function mapResponse(FormResponse $response): array
    {
        $this->responses[$response->id]["id"] = $response->id;
        $this->responses[$response->id]["created_at"] = $response->created_at;

        $this->questions->each(function ($question) use ($response, &$count) {
            $this->addResponse($question, $response);
        });

        return $this->responses;
    }

    /**
     * @param Question     $question
     * @param FormResponse $response
     */
    private function addResponse(Question $question, FormResponse $response): void
    {
        if (isset($response->response->{$question->id})) {
            $this->responses[$response->id]
            ["answers"]
            [$question->order + 1 . '. ' . $question->title] = $response->response->{$question->id};
        } else {
            $this->responses[$response->id]
            ["answers"]
            [$question->order + 1 . '. ' . $question->title] = "n/a";
        }
    }
}