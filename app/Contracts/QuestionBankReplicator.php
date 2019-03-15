<?php

namespace App\Contracts;

interface QuestionBankReplicator
{
    /**
     * @param array $questions
     * @param int   $formId
     */
    public static function addQuestions(array $questions, int $formId): void;
}
