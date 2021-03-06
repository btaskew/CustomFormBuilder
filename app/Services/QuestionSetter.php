<?php

namespace App\Services;

use App\Contracts\QuestionSetter as QuestionSetterContract;
use App\Form;
use App\Http\Requests\QuestionRequest;
use App\Question;

class QuestionSetter implements QuestionSetterContract
{
    /**
     * @param Form            $form
     * @param QuestionRequest $request
     */
    public static function createQuestion(Form $form, QuestionRequest $request): void
    {
        $question = $form->questions()->create($request->only([
            'title', 'type', 'help_text', 'required', 'admin_only'
        ]));

        self::setSelectOptions($question, $request);

        self::setVisibilityRequirement($question, $request);
    }

    /**
     * @param Question        $question
     * @param QuestionRequest $request
     */
    public static function updateQuestion(Question $question, QuestionRequest $request): void
    {
        $question->update($request->only([
            'title', 'type', 'help_text', 'required', 'admin_only'
        ]));

        self::setSelectOptions($question, $request);

        self::setVisibilityRequirement($question, $request);
    }

    /**
     * @param QuestionRequest $request
     * @param int|null        $id
     */
    public static function setBankQuestion(QuestionRequest $request, int $id = null): void
    {
        $attributes = array_merge(
            ['form_id' => null, 'in_question_bank' => true, 'order' => 0],
            $request->only(['title', 'type', 'help_text', 'required', 'admin_only'])
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
    private static function setVisibilityRequirement(Question $question, QuestionRequest $request): void
    {
        if (!$request->hasVisibilityRequirement()) {
            optional($question->visibilityRequirement)->delete();
            return;
        }

        $question->setVisibilityRequirement(
            $request->input('required_question'),
            $request->input('required_value')
        );
    }
}
