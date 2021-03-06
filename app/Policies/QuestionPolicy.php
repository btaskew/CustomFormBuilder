<?php

namespace App\Policies;

use App\Question;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function administer(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * @param User     $user
     * @param Question $question
     * @return bool
     */
    public function update(User $user, Question $question): bool
    {
        return $user->hasAccessTo('update', $question->form);
    }
}
