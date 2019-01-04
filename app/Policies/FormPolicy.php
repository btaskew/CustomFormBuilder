<?php

namespace App\Policies;

use App\Form;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param Form $form
     * @return bool
     */
    public function createQuestion(User $user, Form $form)
    {
        return $user->id == $form->user_id;
    }
}
