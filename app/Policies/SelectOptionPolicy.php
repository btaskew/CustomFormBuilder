<?php

namespace App\Policies;

use App\SelectOption;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SelectOptionPolicy
{
    use HandlesAuthorization;

    /**
     * @param User         $user
     * @param SelectOption $option
     * @return bool
     */
    public function edit(User $user, SelectOption $option): bool
    {
        return $user->hasAccessTo('edit', $option->question->form);
    }
}
