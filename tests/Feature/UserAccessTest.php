<?php

namespace Tests\Feature;

use App\Form;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAccessTest extends TestCase
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
            ->assertStatus(200);

        $this->assertDatabaseHas('form_users', [
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
}
