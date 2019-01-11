<?php

namespace App\Specifications;

use App\Question;

class CanSetVisibilityRequirement
{
    /**
     * @param array $requirement
     * @return bool
     */
    public static function isSatisfiedBy(array $requirement): bool
    {
        if (!static::requiredQuestionExists($requirement['question'])) {
            throw new \InvalidArgumentException("Required question does not exist");
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
     * @param int    $questionId
     * @param string $value
     * @return bool
     */
    private static function requiredValueExists(int $questionId, string $value): bool
    {
        return Question::find($questionId)->options->contains('value', $value);
    }
}