<?php

namespace Tests\Feature\Form;

use App\Form;
use App\FormUser;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateFormAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cant_grant_access_to_a_form()
    {
        $this->post('forms/1/access', ['username' => 'ab123'])->assertRedirect('login');
    }

    /** @test */
    public function a_forms_owner_can_grant_another_user_access_to_their_form()
    {
        $form = $this->loginUserWithForm();
        $user = create(User::class);

        $this->post(formPath($form) . '/access', ['username' => $user->username, 'access' => 'view'])
            ->assertStatus(200)
            ->assertSee($user->username);

        $this->assertDatabaseHas('form_user', [
            'form_id' => $form->id,
            'user_id' => $user->id,
            'access' => 'view'
        ]);
    }

    /** @test */
    public function a_user_cant_grant_access_to_another_users_form()
    {
        $form = create(Form::class, ['user_id' => 999]);

        $this->login()
            ->post(formPath($form) . '/access', ['username' => 'ab123', 'access' => 'view'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('form_user', ['form_id' => $form->id]);
    }

    /** @test */
    public function a_user_can_grant_access_to_a_form_they_have_edit_access_to()
    {
        $form = $this->createFormWithAccess('edit');
        $user = create(User::class);

        $this->post(formPath($form) . '/access', ['username' => $user->username, 'access' => 'view'])
            ->assertStatus(200);

        $this->assertDatabaseHas('form_user', [
            'form_id' => $form->id,
            'user_id' => $user->id,
            'access' => 'view'
        ]);
    }

    /** @test */
    public function a_user_cant_grant_access_to_a_form_they_have_view_access_to()
    {
        $form = $this->createFormWithAccess('view');
        $user = create(User::class);

        $this->post(formPath($form) . '/access', ['username' => $user->username, 'access' => 'view'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('form_user', ['user_id' => $user->id]);
    }

    /** @test */
    public function a_user_cant_grant_access_to_a_non_existing_user()
    {
        $form = $this->loginUserWithForm();

        $this->post(formPath($form) . '/access', ['username' => 'ab123', 'access' => 'view'])
            ->assertStatus(404)
            ->assertJsonFragment(['error' => 'Given user was not found in the database']);
    }

    /** @test */
    public function a_user_cant_grant_access_to_themselves()
    {
        $form = $this->loginUserWithForm();

        $this->post(formPath($form) . '/access', ['username' => $form->owner->username, 'access' => 'view'])
            ->assertStatus(422)
            ->assertJsonFragment(['error' => 'Can\'t grant access to self']);
    }
}