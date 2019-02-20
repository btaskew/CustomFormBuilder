<?php

namespace Tests\Feature;

use App\Form;
use App\FormUser;
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
    public function a_user_cant_preview_another_users_complete_form()
    {
        $this->withExceptionHandling();

        $form = create(Form::class, ['user_id' => 999]);

        $this->login()
            ->get(formPath($form) . '/preview')
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_preview_a_form_they_have_view_access_to()
    {
        $this->login();
        $form = create(Form::class, ['user_id' => 999]);
        create(FormUser::class, [
            'user_id' => auth()->user()->id,
            'form_id' => $form->id,
            'access' => 'view'
        ]);

        $this->get(formPath($form) . '/preview')
            ->assertStatus(200)
            ->assertSee($form->title);
    }
}
