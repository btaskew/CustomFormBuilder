<?php

namespace Tests\Feature;

use App\Form;
use App\Question;
use App\SelectOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateQuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cant_add_questions()
    {
        $this->withExceptionHandling();

        $this->post('/forms/1/questions', [])
            ->assertStatus(302);
    }

    /** @test */
    public function a_user_can_create_questions()
    {
        $form = $this->loginUserWithForm();

        $attributes = [
            'title' => 'First question',
            'type' => 'text',
            'help_text' => 'Help text',
            'required' => true,
            'admin_only' => false,
            'order' => 2
        ];

        $this->post('/forms/' . $form->id . '/questions', $attributes)
            ->assertStatus(200);

        $this->assertDatabaseHas('questions', $attributes);
    }

    /** @test */
    public function a_user_can_create_a_select_question()
    {
        $form = $this->loginUserWithForm();

        $attributes = [
            'title' => 'First question',
            'type' => 'radio',
            'help_text' => 'Help text',
            'required' => true,
            'admin_only' => false,
            'order' => 2,
            'options' => [
                ['value' => 'a', 'display_value' => 'Value a']
            ]
        ];

        $this->post('/forms/' . $form->id . '/questions', $attributes)
            ->assertStatus(200);

        $this->assertDatabaseHas('questions', ['title' => 'First question']);
        $this->assertDatabaseHas('select_options', ['value' => 'a', 'display_value' => 'Value a']);
    }

    /** @test */
    public function a_user_cant_create_questions_to_another_users_form()
    {
        $this->withExceptionHandling();

        $form = create(Form::class, ['user_id' => 9999]);
        $attributes = raw(Question::class);

        $this->login()
            ->post('/forms/' . $form->id . '/questions', $attributes)
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_edit_a_question()
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
    public function a_user_can_edit_a_select_questions_options()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['title' => 'Old title', 'type' => 'radio', 'form_id' => $form->id]);
        $option = create(SelectOption::class, ['display_value' => 'Old value', 'question_id' => $question->id]);

        $this->patch(
            '/forms/' . $question->form->id . '/questions/' . $question->id,
            [
                'title' => 'New title',
                'type' => 'radio',
                'options' => [['id' => $option->id, 'value' => 'value', 'display_value' => 'New value']]
            ]
        )->assertStatus(200);

        $this->assertEquals('New value', $option->fresh()->display_value);
    }

    /** @test */
    public function a_user_can_add_a_new_option_to_a_select_question()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['title' => 'Old title', 'type' => 'radio', 'form_id' => $form->id]);

        $this->patch(
            '/forms/' . $question->form->id . '/questions/' . $question->id,
            [
                'title' => 'New title',
                'type' => 'radio',
                'options' => [['id' => null, 'value' => 'value', 'display_value' => 'New value']]
            ]
        )->assertStatus(200);

        $this->assertDatabaseHas('select_options', ['display_value' => 'New value']);
    }

    /** @test */
    public function a_user_can_delete_an_option_from_a_select_question()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['title' => 'Old title', 'type' => 'radio', 'form_id' => $form->id]);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $this->delete('/forms/' . $question->form->id . '/questions/' . $question->id .'/options/' . $option->id)
            ->assertStatus(200);

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
                'title' => 'New title', 'type' => 'text'
            ])
            ->assertStatus(403);

        $this->assertEquals('Old title', $question->fresh()->title);
    }
}