<?php

namespace App\Services;

use App\Contracts\FormResponder as FormResponderContract;
use App\Form;
use App\Http\Requests\ResponseRequest;
use App\Question;
use Carbon\Carbon;

class FormResponder implements FormResponderContract
{
    /**
     * @param ResponseRequest $response
     * @param Form            $form
     */
    public function saveResponse(ResponseRequest $response, Form $form): void
    {
        $answers = [];

        $form->getAnswerableQuestions(['id', 'type'])
            ->each(function (Question $question) use ($response, &$answers) {
                $this->addResponse($question, $response, $answers);
            });

        $form->responses()->create([
            'response' => $answers
        ]);
    }

    /**
     * @param Question        $question
     * @param ResponseRequest $response
     * @param array           $answers
     */
    private function addResponse(Question $question, ResponseRequest $response, array &$answers): void
    {
        $answers[$question->id] = $response->getQuestionsResponse($question->id);

        if ($question->type == 'date') {
            $answers[$question->id] = (Carbon::createFromTimestampMs($answers[$question->id]))->toDateString();
        }
    }
}
