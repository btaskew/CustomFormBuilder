<?php

namespace Tests\Feature\Form;

use App\Form;
use App\FormUser;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteFormAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cant_remove_a_users_access_to_a_form()
    {
        $form = create(Form::class);
        $userAcccess = create(FormUser::class, ['form_id' => $form->id]);

        $this->delete(formPath($form) . '/access/' . $userAcccess->id)->assertRedirect('login');

        $this->assertDatabaseHas('form_user', ['id' => $userAcccess->id]);
    }

    /** @test */
    public function a_user_can_remove_another_users_access_to_their_form()
    {
        $form = $this->loginUserWithForm();
        $user = create(User::class);
        $userAcccess = create(FormUser::class, ['form_id' => $form->id, 'user_id' => $user->id]);

        $this->delete(formPath($form) . '/access/' . $userAcccess->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('form_user', ['id' => $userAcccess->id]);
    }

    /** @test */
    public function a_user_cant_remove_a_users_access_to_another_users_form()
    {
        $form = create(Form::class, ['user_id' => 999]);
        $userAcccess = create(FormUser::class, ['form_id' => $form->id, 'user_id' => 123]);

        $this->login()
            ->delete(formPath($form) . '/access/' . $userAcccess->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('form_user', ['id' => $userAcccess->id]);
    }

    /** @test */
    public function a_user_can_remove_a_users_access_to_another_users_form_they_have_update_access_to()
    {
        $form = $this->createFormWithAccess('update');
        $userAcccess = create(FormUser::class, ['form_id' => $form->id, 'user_id' => 123]);

        $this->delete(formPath($form) . '/access/' . $userAcccess->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('form_user', ['id' => $userAcccess->id]);
    }

    /** @test */
    public function a_user_cant_remove_a_users_access_to_another_users_form_they_have_view_access_to()
    {
        $form = $this->createFormWithAccess('view');
        $userAcccess = create(FormUser::class, ['form_id' => $form->id, 'user_id' => 123]);

        $this->delete(formPath($form) . '/access/' . $userAcccess->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('form_user', ['id' => $userAcccess->id]);
    }
}
