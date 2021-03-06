<?php

namespace Tests;

use App\Form;
use App\FormUser;
use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @return $this
     */
    public function loginAdmin()
    {
        $user = create(User::class);
        $user->assignRole('standard_user')
            ->assignRole('admin');

        return $this->actingAs($user);
    }

    /**
     * @return $this
     */
    public function login()
    {
        $user = create(User::class);
        $user->assignRole('standard_user');

        return $this->actingAs($user);
    }

    /**
     * @return Form
     */
    public function loginUserWithForm(): Form
    {
        $this->login();
        return create(Form::class, ['user_id' => auth()->id()]);
    }

    /**
     * Create another users form the authenticated user has access to
     *
     * @param string $access
     * @return Form
     */
    public function createFormWithAccess(string $access): Form
    {
        $this->login();
        $form = create(Form::class, ['user_id' => 999]);
        create(FormUser::class, [
            'user_id' => auth()->id(),
            'form_id' => $form->id,
            'access' => $access
        ]);
        return $form;
    }
}
