<?php

namespace Tests\Feature\Form;

use App\Form;
use App\FormUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cant_delete_a_form()
    {
        $form = create(Form::class);

        $this->delete(formPath($form))->assertRedirect('login');

        $this->assertDatabaseHas('forms', ['id' => $form->id]);
    }

    /** @test */
    public function a_user_can_delete_one_of_their_forms()
    {
        $form = $this->loginUserWithForm();

        $this->delete(formPath($form))->assertStatus(200);

        $this->assertDatabaseMissing('forms', ['id' => $form->id]);
    }

    /** @test */
    public function a_user_cant_delete_another_users_form()
    {
        $form = create(Form::class, ['user_id' => 9999]);

        $this->login()->delete(formPath($form))->assertStatus(403);

        $this->assertDatabaseHas('forms', ['id' => $form->id]);
    }

    /** @test */
    public function a_user_can_delete_a_form_they_have_edit_access_to()
    {
        $form = $this->createFormWithAccess('edit');

        $this->delete(formPath($form))->assertStatus(200);

        $this->assertDatabaseMissing('forms', ['id' => $form->id]);
    }

    /** @test */
    public function a_user_cant_delete_a_form_they_have_view_access_to()
    {
        $form = $this->createFormWithAccess('view');

        $this->delete(formPath($form))->assertStatus(403);

        $this->assertDatabaseHas('forms', ['id' => $form->id]);
    }
}
