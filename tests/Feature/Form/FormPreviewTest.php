<?php

namespace Tests\Feature\Form;

use App\Form;
use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormPreviewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_preview_their_complete_form()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->get(formPath($form) . '/preview')
            ->assertStatus(200)
            ->assertSee($form->title)
            ->assertSee($question->title);
    }

    /** @test */
    public function a_guest_cant_preview_a_form()
    {
        $this->get('/forms/1/preview')->assertRedirect('login');
    }

    /** @test */
    public function a_user_cant_preview_another_users_complete_form()
    {
        $form = create(Form::class, ['user_id' => 999]);

        $this->login()
            ->get(formPath($form) . '/preview')
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_preview_a_form_they_have_view_access_to()
    {
        $form = $this->createFormWithAccess('view');

        $this->get(formPath($form) . '/preview')
            ->assertStatus(200)
            ->assertSee($form->title);
    }
}
