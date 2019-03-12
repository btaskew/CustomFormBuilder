<?php

namespace Tests\Feature\Form;

use App\Form;
use App\FormUser;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewFormAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cant_view_a_forms_list_of_users()
    {
        $this->get('/forms/1/access')->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_view_all_users_who_have_access_to_their_form()
    {
        $form = $this->loginUserWithForm();
        $user = create(User::class);
        create(FormUser::class, ['form_id' => $form->id, 'user_id' => $user->id]);

        $this->get(formPath($form) . '/access')
            ->assertStatus(200)
            ->assertSee($user->username);
    }

    /** @test */
    public function a_user_cant_view_users_who_have_access_to_another_users_form()
    {
        $form = create(Form::class);

        $this->login()->get(formPath($form) . '/access')->assertStatus(403);
    }

    /** @test */
    public function a_user_can_view_another_users_forms_list_of_users_if_they_have_update_access()
    {
        $form = $this->createFormWithAccess('update');

        $this->get(formPath($form) . '/access')->assertStatus(200);
    }

    /** @test */
    public function a_user_cant_view_another_users_forms_list_of_users_if_they_have_view_access()
    {
        $form = $this->createFormWithAccess('view');

        $this->get(formPath($form) . '/access')->assertStatus(403);
    }
}
