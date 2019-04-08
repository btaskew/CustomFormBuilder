<?php

namespace App\Specifications;

use App\Form;
use App\Question;

class CanSetResponseEmailField
{
    /**
     * @param int  $questionId
     * @param Form $form
     * @return bool
     */
    public static function isSatisfiedBy(?int $questionId, Form $form): bool
    {
        if (is_null($questionId)) {
            return true;
        }

        if (static::questionDoesNotExist($questionId)) {
            return false;
        }

        if (static::questionDoesNotExistOnForm($questionId, $form)) {
            return false;
        }

        if (static::questionIsWrongType($questionId)) {
            return false;
        }

        return true;
    }

    private static function questionDoesNotExist(int $questionId): bool
    {
        return !Question::where('id', $questionId)->exists();
    }

    /**
     * @param int  $questionId
     * @param Form $form
     * @return bool
     */
    private static function questionDoesNotExistOnForm(int $questionId, Form $form): bool
    {
        return !$form->questions->contains('id', $questionId);
    }

    /**
     * @param int $questionId
     * @return bool
     */
    private static function questionIsWrongType(int $questionId): bool
    {
        return Question::find($questionId)->type !== 'email';
    }
}
