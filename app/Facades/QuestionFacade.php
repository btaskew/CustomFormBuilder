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

        self::setSelectOptions($question, $request);

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

        self::setSelectOptions($question, $request);

        self::updateVisibilityRequirement($question, $request);
    }

    /**
     * @param QuestionRequest $request
     * @param int|null        $id
     */
    public static function setBankQuestion(QuestionRequest $request, int $id = null)
    {
        $attributes = array_merge(
            ['form_id' => null, 'in_question_bank' => true, 'order' => 0],
            $request->only([
                'title', 'type', 'help_text', 'required', 'admin_only'
            ])
        );

        $question = Question::updateOrCreate(['id' => $id], $attributes);

        self::setSelectOptions($question, $request);
    }

    /**
     * @param Question        $question
     * @param QuestionRequest $request
     */
    private static function setSelectOptions(Question $question, QuestionRequest $request): void
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