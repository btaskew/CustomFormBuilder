<?php

namespace App\Policies;

use App\Form;
use App\Question;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can update the form
     * Also used to authorise adding and editing of form's questions
     *
     * @param User $user
     * @param Form $form
     * @return bool
     */
    public function update(User $user, Form $form): bool
    {
        return $user->id == $form->user_id;
    }
}
