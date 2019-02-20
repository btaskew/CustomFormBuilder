<?php

namespace Tests\Feature;

use App\Form;
use App\FormUser;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormUserAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cant_grant_access_to_a_form()
    {
        $this->withExceptionHandling();

        $this->post('forms/1/access', ['username' => 'ab123'])
            ->assertRedirect('login');
    }

    /** @test */
    public function a_forms_owner_can_grant_another_user_access_to_their_form()
    {
        $form = $this->loginUserWithForm();
        $user = create(User::class);

        $this->post('forms/' . $form->id . '/access', ['username' => $user->username])
            ->assertStatus(200)
            ->assertSee($user->username);

        $this->assertDatabaseHas('form_user', [
            'form_id' => $form->id,
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function a_user_cant_grant_access_to_another_users_form()
    {
        $this->withExceptionHandling();

        $form = create(Form::class, ['user_id' => 999]);

        $this->login()
            ->post('forms/' . $form->id . '/access', ['username' => 'ab123'])
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_cant_grant_access_to_a_non_existing_user()
    {
        $form = $this->loginUserWithForm();

        $this->post('forms/' . $form->id . '/access', ['username' => 'ab123'])
            ->assertStatus(404)
            ->assertJsonFragment(['error' => 'Given user was not found in the database']);
    }

    /** @test */
    public function a_user_cant_grant_access_to_themselves()
    {
        $form = $this->loginUserWithForm();

        $this->post('forms/' . $form->id . '/access', ['username' => $form->owner->username])
            ->assertStatus(422)
            ->assertJsonFragment(['error' => 'Can\'t grant access to self']);
    }

    /** @test */
    public function a_user_can_view_all_users_who_have_access_to_a_given_form()
    {
        $form = $this->loginUserWithForm();
        $user = create(User::class);
        create(FormUser::class, ['form_id' => $form->id, 'user_id' => $user->id]);

        $this->get('forms/' . $form->id . '/access')
            ->assertStatus(200)
            ->assertSee($user->username);
    }

    /** @test */
    public function a_user_can_remove_another_users_access_to_their_form()
    {
        $form = $this->loginUserWithForm();
        $user = create(User::class);
        $userAcccess = create(FormUser::class, ['form_id' => $form->id, 'user_id' => $user->id]);

        $this->delete('forms/' . $form->id . '/access/' . $userAcccess->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('form_user', ['id' => $userAcccess->id]);
    }

    /** @test */
    public function a_user_cant_remove_a_users_access_to_another_users_form()
    {
        $this->withExceptionHandling();

        $form = create(Form::class, ['user_id' => 999]);
        $userAcccess = create(FormUser::class, ['form_id' => $form->id, 'user_id' => 123]);

        $this->login()
            ->delete('forms/' . $form->id . '/access/' . $userAcccess->id)
            ->assertStatus(403);
    }
}
