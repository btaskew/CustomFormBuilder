<?php

namespace App\Policies;

use App\Form;
use App\FormUser;
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
    public function edit(User $user, Form $form): bool
    {
        return $user->hasAccessTo('edit', $form);
    }

    /**
     * @param User $user
     * @param Form $form
     * @return bool
     */
    public function view(User $user, Form $form): bool
    {
        return $user->hasAccessTo('view', $form);
    }
}
