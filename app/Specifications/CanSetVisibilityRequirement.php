<?php

namespace App\Specifications;

use App\Question;

class CanSetVisibilityRequirement
{
    /**
     * @param array $requirement
     * @param int   $formId
     * @return bool
     */
    public static function isSatisfiedBy(array $requirement, int $formId): bool
    {
        if (!static::requiredQuestionExists($requirement['question'])) {
            throw new \InvalidArgumentException("Required question does not exist");
        }

        if (!static::requiredQuestionOnSameForm($requirement['question'], $formId)) {
            throw new \InvalidArgumentException("Required question is on a different form");
        }

        if (!static::requiredValueExists($requirement['question'], $requirement['value'])) {
            throw new \InvalidArgumentException("Required value does not exist");
        }

        return true;
    }

    /**
     * @param int $questionId
     * @return bool
     */
    private static function requiredQuestionExists(int $questionId): bool
    {
        return Question::where('id', $questionId)->exists();
    }

    /**
     * @param int $questionId
     * @return bool
     */
    private static function requiredQuestionOnSameForm(int $questionId, int $formId): bool
    {
        return Question::find($questionId)->form_id == $formId;
    }

    /**
     * @param int    $questionId
     * @param string $value
     * @return bool
     */
    private static function requiredValueExists(int $questionId, string $value): bool
    {
        return Question::find($questionId)->options->contains('value', $value);
    }
}