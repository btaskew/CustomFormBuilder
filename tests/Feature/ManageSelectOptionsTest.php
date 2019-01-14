<?php

namespace Tests\Feature;

use App\Form;
use App\Question;
use App\SelectOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageSelectOptionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_edit_a_select_questions_options()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);
        $option = create(SelectOption::class, ['display_value' => 'Old value', 'question_id' => $question->id]);

        $this->patch(
            '/forms/' . $question->form->id . '/questions/' . $question->id,
            [
                'title' => 'New title',
                'type' => 'radio',
                'order' => 2,
                'options' => [['id' => $option->id, 'value' => 'value', 'display_value' => 'New value']]
            ]
        )->assertStatus(200);

        $this->assertEquals('New value', $option->fresh()->display_value);
    }

    /** @test */
    public function a_user_can_add_a_new_option_to_a_select_question()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);

        $this->patch(
            '/forms/' . $question->form->id . '/questions/' . $question->id,
            [
                'title' => 'New title',
                'type' => 'radio',
                'order' => 2,
                'options' => [['id' => null, 'value' => 'value', 'display_value' => 'New value']]
            ]
        )->assertStatus(200);

        $this->assertDatabaseHas('select_options', ['display_value' => 'New value']);
    }

    /** @test */
    public function a_user_cant_add_a_new_option_to_another_users_question()
    {
        $this->withExceptionHandling();

        $form = create(Form::class, ['user_id' => 9999]);
        $question = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);

        $this->login()->patch(
            '/forms/' . $question->form->id . '/questions/' . $question->id,
            [
                'title' => 'New title',
                'type' => 'radio',
                'order' => 2,
                'options' => [['id' => null, 'value' => 'value', 'display_value' => 'New value']]
            ]
        )->assertStatus(403);
    }

    /** @test */
    public function a_user_can_delete_an_option_from_a_select_question()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $this->delete('/forms/' . $question->form->id . '/questions/' . $question->id .'/options/' . $option->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('select_options', ['id' => $option->id]);
    }

    /** @test */
    public function a_guest_cant_delete_a_select_option()
    {
        $this->withExceptionHandling();

        $form = create(Form::class);
        $question = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $this->delete('/forms/' . $question->form->id . '/questions/' . $question->id .'/options/' . $option->id)
            ->assertRedirect('login');

        $this->assertDatabaseHas('select_options', ['id' => $option->id]);
    }

    /** @test */
    public function a_user_cant_delete_an_option_for_another_users_form()
    {
        $this->withExceptionHandling();

        $form = create(Form::class, ['user_id' => 9999]);
        $question = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $this->login()
            ->delete('/forms/' . $question->form->id . '/questions/' . $question->id .'/options/' . $option->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('select_options', ['id' => $option->id]);
    }

    /** @test */
    public function a_questions_select_options_are_deleted_when_the_question_is_deleted()
    {
        $question = create(Question::class);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $question->delete();

        $this->assertDatabaseMissing('select_options', ['id' => $option->id]);
    }

    /** @test */
    public function a_questions_select_options_are_deleted_when_the_type_is_changed()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => $form->id]);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $this->patch(
            '/forms/' . $form->id . '/questions/' . $question->id,
            ['title' => 'New title', 'type' => 'text', 'help_text' => '']
        )->assertStatus(200);

        $this->assertDatabaseMissing('select_options', ['id' => $option->id]);
    }
}