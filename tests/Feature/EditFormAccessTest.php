<?php

namespace Tests\Feature;

use App\Form;
use App\FormUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditFormAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_edit_another_users_form_that_they_have_edit_access_to()
    {
        $this->login();
        $form = create(Form::class, ['user_id' => 999]);
        create(FormUser::class, [
            'user_id' => auth()->user()->id,
            'form_id' => $form->id,
            'access' => 'edit'
        ]);

        $attributes = [
            'title' => 'New title',
            'open_date' => '1990-01-01',
            'close_date' => '1990-01-02',
            'active' => false
        ];

        $this->patch(formPath($form), $attributes)
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_cant_edit_another_users_form_that_they_have_view_access_to()
    {
        $this->withExceptionHandling();

        $this->login();
        $form = create(Form::class, ['user_id' => 999]);
        create(FormUser::class, [
            'user_id' => auth()->user()->id,
            'form_id' => $form->id,
            'access' => 'view'
        ]);

        $attributes = [
            'title' => 'New title',
            'open_date' => '1990-01-01',
            'close_date' => '1990-01-02',
            'active' => false
        ];

        $this->patch(formPath($form), $attributes)
            ->assertStatus(403);
    }
}
