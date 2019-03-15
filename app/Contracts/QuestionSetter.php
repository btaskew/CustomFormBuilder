<?php

namespace App\Contracts;

use App\Form;
use App\Http\Requests\QuestionRequest;
use App\Question;

interface QuestionSetter
{
    /**
     * @param Form            $form
     * @param QuestionRequest $request
     */
    public static function createQuestion(Form $form, QuestionRequest $request): void;


    /**
     * @param Question        $question
     * @param QuestionRequest $request
     */
    public static function updateQuestion(Question $question, QuestionRequest $request): void;

    /**
     * @param QuestionRequest $request
     * @param int|null        $id
     */
    public static function setBankQuestion(QuestionRequest $request, int $id = null): void;
}
