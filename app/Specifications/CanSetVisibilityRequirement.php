<?php

namespace App\Specifications;

use App\Question;

class CanSetVisibilityRequirement
{
    /**
     * @param array    $requirement
     * @param Question $question
     * @return bool
     */
    public static function isSatisfiedBy(array $requirement, Question $question): bool
    {
        if (static::settingRequirementAgainstSelf($requirement['question'], $question->id)) {
            throw new \InvalidArgumentException("Can't set requirement against self");
        }

        if (!static::requiredQuestionExists($requirement['question'])) {
            throw new \InvalidArgumentException("Required question does not exist");
        }

        if (!static::requiredQuestionOnSameForm($requirement['question'], $question->form_id)) {
            throw new \InvalidArgumentException("Required question is on a different form");
        }

        if (!static::requiredValueExists($requirement['question'], $requirement['value'])) {
            throw new \InvalidArgumentException("Required value does not exist");
        }

        return true;
    }

    /**
     * @param int $requiredQuestionId
     * @param int $questionId
     * @return bool
     */
    private static function settingRequirementAgainstSelf(int $requiredQuestionId, int $questionId)
    {
        return $requiredQuestionId === $questionId;
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