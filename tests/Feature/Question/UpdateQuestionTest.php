<?php

namespace Tests\Feature\Question;

use App\Form;
use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateQuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cant_edit_a_question()
    {
        $form = create(Form::class);
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->patch('/forms/' . $question->form->id . '/questions/' . $question->id, [])
            ->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_edit_a_question_for_their_form()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['title' => 'Old title', 'form_id' => $form->id]);

        $this->patch(
            '/forms/' . $question->form->id . '/questions/' . $question->id,
            ['title' => 'New title', 'type' => 'text']
        )->assertStatus(200);

        $this->assertEquals('New title', $question->fresh()->title);
    }

    /** @test */
    public function a_user_can_clear_the_help_text_field()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->patch(
            '/forms/' . $question->form->id . '/questions/' . $question->id,
            ['title' => 'New title', 'type' => 'text', 'help_text' => '']
        )->assertStatus(200);

        $this->assertEquals('', $question->fresh()->help_text);
    }

    /** @test */
    public function a_user_cant_edit_questions_that_belong_to_another_users_form()
    {
        $form = create(Form::class, ['user_id' => 9999]);
        $question = create(Question::class, ['title' => 'Old title', 'type' => 'text', 'form_id' => $form->id]);

        $this->login()
            ->patch(formPath($form) . '/questions/' . $question->id, [
                'title' => 'New title', 'type' => 'text', 'order' => 1
            ])
            ->assertStatus(403);

        $this->assertEquals('Old title', $question->fresh()->title);
    }

    /** @test */
    public function a_user_can_edit_questions_to_a_form_they_have_edit_access_to()
    {
        $form = $this->createFormWithAccess('edit');
        $question = create(Question::class, ['title' => 'Old title', 'form_id' => $form->id]);

        $this->patch(
            '/forms/' . $question->form->id . '/questions/' . $question->id,
            ['title' => 'New title', 'type' => 'text']
        )->assertStatus(200);

        $this->assertEquals('New title', $question->fresh()->title);
    }

    /** @test */
    public function a_user_cant_edit_questions_to_a_form_they_have_view_access_to()
    {
        $form = $this->createFormWithAccess('view');
        $question = create(Question::class, ['title' => 'Old title', 'form_id' => $form->id]);

        $this->patch(
            '/forms/' . $question->form->id . '/questions/' . $question->id,
            ['title' => 'New title', 'type' => 'text']
        )->assertStatus(403);

        $this->assertEquals('Old title', $question->fresh()->title);
    }
}