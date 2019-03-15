<?php

namespace App\Services;

use App\FormResponse;
use App\Objects\FormattedResponse;
use App\Question;
use Illuminate\Support\Collection;

class ResponseFormatter
{
    /**
     * @var Collection
     */
    private $questions;

    /**
     * @var array
     */
    private $responses;

    /**
     * @param Collection $questions
     */
    public function __construct(Collection $questions)
    {
        $this->questions = $questions;
        $this->responses = [];
    }

    /**
     * @param array $responses
     * @return array
     */
    public function formatResponses(array $responses): array
    {
        foreach ($responses as $response) {
            $this->formatResponse($response);
        }

        return $this->responses;
    }

    /**
     * @param FormResponse $response
     */
    private function formatResponse(FormResponse $response): void
    {
        $formattedResponse = $this->setupFormattedResponse($response);

        $this->addAnswers($response, $formattedResponse);

        $this->responses[$response->id] = $formattedResponse;
    }

    /**
     * @param FormResponse $response
     * @return FormattedResponse
     */
    private function setupFormattedResponse(FormResponse $response): FormattedResponse
    {
        return (new FormattedResponse())
            ->setId($response->id)
            ->setTimeRecorded($response->created_at);
    }

    /**
     * @param FormResponse      $response
     * @param FormattedResponse $formattedResponse
     */
    private function addAnswers(FormResponse $response, FormattedResponse $formattedResponse): void
    {
        $this->questions->each(function ($question) use ($response, $formattedResponse) {
            $this->addAnswer($question, $response->response, $formattedResponse);
        });
    }

    /**
     * @param Question          $question
     * @param \stdClass         $answers
     * @param FormattedResponse $response
     */
    private function addAnswer(Question $question, \stdClass $answers, FormattedResponse $response): void
    {
        if (isset($answers->{$question->id})) {
            $response->addAnswer($question->getFullTitle(), $answers->{$question->id});
            return;
        }

        $response->addBlankAnswer($question->getFullTitle());
    }
}