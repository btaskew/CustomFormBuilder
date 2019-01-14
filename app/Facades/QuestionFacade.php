<?php

namespace App\Facades;

use App\Form;
use App\Http\Requests\QuestionRequest;
use App\Question;

class QuestionFacade
{
    /**
     * @param Form            $form
     * @param QuestionRequest $request
     */
    public static function createQuestion(Form $form, QuestionRequest $request)
    {
        $question = $form->questions()->create($request->only([
            'title', 'type', 'help_text', 'required', 'admin_only'
        ]));

        self::setSelectOptions($request, $question);

        if ($request->hasVisibilityRequirement()) {
            $question->setVisibilityRequirement($request->input('required_if'));
        }
    }

    /**
     * @param Question        $question
     * @param QuestionRequest $request
     */
    public static function updateQuestion(Question $question, QuestionRequest $request)
    {
        $question->update($request->only([
            'title', 'type', 'help_text', 'required', 'admin_only'
        ]));

        self::setSelectOptions($request, $question);

        self::updateVisibilityRequirement($question, $request);
    }

    /**
     * @param QuestionRequest $request
     * @param Question        $question
     */
    private static function setSelectOptions(QuestionRequest $request, Question $question): void
    {
        if ($question->isSelectQuestion()) {
            $question->setOptions($request->input('options'));
        }
    }

    /**
     * @param Question        $question
     * @param QuestionRequest $request
     */
    private static function updateVisibilityRequirement(Question $question, QuestionRequest $request): void
    {
        if ($request->hasVisibilityRequirement()) {
            $question->setVisibilityRequirement($request->input('required_if'));
        } else if ($question->visibilityRequirement()->exists()) {
            $question->visibilityRequirement->delete();
        }
    }
}