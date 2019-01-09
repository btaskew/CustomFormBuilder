<?php

namespace Tests\Feature;

use App\Form;
use App\Question;
use App\SelectOption;
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
            ->assertRedirect('login');
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
    public function a_questions_select_options_are_deleted_when_the_type_is_changed()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => $form->id]);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $this->patch(
            '/forms/' . $form->id . '/questions/' . $question->id,
            ['title' => 'New title', 'type' => 'text', 'order' => 1, 'help_text' => '']
        )->assertStatus(200);

        $this->assertDatabaseMissing('select_options', ['id' => $option->id]);
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

    /** @test */
    public function a_guest_cant_delete_a_question()
    {
        $this->withExceptionHandling();

        $form = create(Form::class);
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->delete('/forms/' . $question->form->id . '/questions/' . $question->id)
            ->assertRedirect('login');

        $this->assertDatabaseHas('questions', ['id' => $question->id]);
    }

    /** @test */
    public function a_user_can_delete_a_question()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->delete('/forms/' . $question->form->id . '/questions/' . $question->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('questions', ['id' => $question->id]);
    }

    /** @test */
    public function a_user_cant_delete_a_question_on_another_users_form()
    {
        $this->withExceptionHandling();

        $form = create(Form::class, ['user_id' => 9999]);
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->login()
            ->delete('/forms/' . $question->form->id . '/questions/' . $question->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('questions', ['id' => $question->id]);
    }
}