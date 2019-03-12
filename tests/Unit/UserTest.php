<?php

namespace Tests\Unit;

use App\Form;
use App\FormUser;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_has_forms()
    {
        $user = create(User::class);
        $form = create(Form::class, ['user_id' => $user->id]);

        $this->assertTrue($user->forms->first()->is($form));
    }

    /** @test */
    public function a_user_has_access_to_many_forms()
    {
        $user = create(User::class);
        $form = create(Form::class, ['user_id' => 9999]);
        create(FormUser::class, [
            'user_id' => $user->id,
            'form_id' => $form->id
        ]);

        $this->assertTrue($user->accessibleForms->first()->is($form));
    }

    /** @test */
    public function a_user_can_fetch_all_the_forms_it_has_access_to()
    {
        $user = create(User::class);
        $form = create(Form::class, ['user_id' => $user->id]);
        $form2 = create(Form::class, ['user_id' => 9999]);
        create(FormUser::class, [
            'user_id' => $user->id,
            'form_id' => $form2->id
        ]);

        $forms = $user->getAllForms();
        $this->assertTrue($forms->contains($form));
        $this->assertTrue($forms->contains($form2));
    }

    /** @test */
    public function a_user_can_determine_if_it_has_access_to_a_form()
    {
        $user = create(User::class);
        $form = create(Form::class, ['user_id' => $user->id]);
        $this->assertTrue($user->hasAccessTo('view', $form));

        $form2 = create(Form::class, ['user_id' => 999]);
        $this->assertFalse($user->hasAccessTo('view', $form2));

        create(FormUser::class, [
            'user_id' => $user->id,
            'form_id' => $form2->id,
            'access' => 'view'
        ]);
        $this->assertTrue($user->hasAccessTo('view', $form2));
    }

    /** @test */
    public function if_a_user_has_update_access_they_also_have_view_access()
    {
        $user = create(User::class);
        $form = create(Form::class, ['user_id' => 999]);
        create(FormUser::class, [
            'user_id' => $user->id,
            'form_id' => $form->id,
            'access' => 'update'
        ]);
        $this->assertTrue($user->hasAccessTo('view', $form));
    }
}