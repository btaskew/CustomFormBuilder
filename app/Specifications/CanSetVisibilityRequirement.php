<?php

namespace App\Specifications;

use App\Question;

class CanSetVisibilityRequirement
{
    /**
     * @param int      $questionId
     * @param string   $questionValue
     * @param Question $question
     * @return bool
     */
    public static function isSatisfiedBy(int $questionId, string $questionValue, Question $question): bool
    {
        if (static::settingRequirementAgainstSelf($questionId, $question->id)) {
            return false;
        }

        if (static::requiredQuestionDoesntExists($questionId)) {
            return false;
        }

        if (static::requiredQuestionOnDifferentForm($questionId, $question->form_id)) {
            return false;
        }

        if (static::requiredValueDoesntExists($questionId, $questionValue)) {
            return false;
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
    private static function requiredQuestionDoesntExists(int $questionId): bool
    {
        return !Question::where('id', $questionId)->exists();
    }

    /**
     * @param int $questionId
     * @param int $formId
     * @return bool
     */
    private static function requiredQuestionOnDifferentForm(int $questionId, int $formId): bool
    {
        return Question::find($questionId)->form_id != $formId;
    }

    /**
     * @param int    $questionId
     * @param string $value
     * @return bool
     */
    private static function requiredValueDoesntExists(int $questionId, string $value): bool
    {
        return !Question::find($questionId)->options->contains('value', $value);
    }
}