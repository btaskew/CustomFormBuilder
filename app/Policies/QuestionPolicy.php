<?php

namespace App\Policies;

use App\Question;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    /**
     * @param User     $user
     * @param Question $question
     * @return bool
     */
    public function edit(User $user, Question $question): bool
    {
        return $user->hasAccessTo('edit', $question->form);
    }
}
