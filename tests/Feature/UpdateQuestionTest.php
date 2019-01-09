<?php

namespace Tests\Feature;

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
        $this->withExceptionHandling();

        $form = create(Form::class);
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->patch('/forms/' . $question->form->id . '/questions/' . $question->id,[])
            ->assertStatus(302);
    }

    /** @test */
    public function a_user_can_edit_a_question()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['title' => 'Old title', 'form_id' => $form->id]);

        $this->patch(
            '/forms/' . $question->form->id . '/questions/' . $question->id,
            ['title' => 'New title', 'type' => 'text', 'order' => 1]
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
            ['title' => 'New title', 'type' => 'text', 'order' => 1, 'help_text' => '']
        )->assertStatus(200);

        $this->assertEquals('', $question->fresh()->help_text);
    }

    /** @test */
    public function a_user_cant_edit_questions_that_belong_to_another_users_form()
    {
        $this->withExceptionHandling();

        $form = create(Form::class, ['user_id' => 9999]);
        $question = create(Question::class, ['title' => 'Old title', 'type' => 'text', 'form_id' => $form->id]);

        $this->login()
            ->patch('/forms/' . $form->id . '/questions/' . $question->id, [
                'title' => 'New title', 'type' => 'text', 'order' => 1
            ])
            ->assertStatus(403);

        $this->assertEquals('Old title', $question->fresh()->title);
    }
}