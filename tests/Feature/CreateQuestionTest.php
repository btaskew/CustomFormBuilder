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
    public function a_user_can_view_the_create_question_page_for_their_form()
    {
        $form = $this->loginUserWithForm();

        $this->get(formPath($form) . '/questions/create')->assertSee('Create form question');
    }

    /** @test */
    public function a_user_cant_view_the_create_question_page_for_another_users_form()
    {
        $this->withExceptionHandling();
        $form = create(Form::class, ['user_id' => 999]);

        $this->login()->get(formPath($form) . '/questions/create')->assertStatus(403);
    }

    /** @test */
    public function a_guest_cant_add_questions()
    {
        $this->withExceptionHandling();

        $this->post('/forms/1/questions', [])
            ->assertRedirect('login');
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
        ];

        $this->post(formPath($form) . '/questions', $attributes)
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
            'options' => [
                ['value' => 'a', 'display_value' => 'Value a']
            ]
        ];

        $this->post(formPath($form) . '/questions', $attributes)
            ->assertStatus(200);

        $this->assertDatabaseHas('questions', ['title' => 'First question']);
        $this->assertDatabaseHas('select_options', ['value' => 'a', 'display_value' => 'Value a']);
    }

    /** @test */
    public function a_user_can_create_a_question_with_a_visibility_requirement()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $attributes = [
            'title' => 'First question',
            'type' => 'text',
            'help_text' => 'Help text',
            'required' => true,
            'required_if' => [
                'question' => $question->id,
                'value' => $option->value
            ]
        ];

        $this->post(formPath($form) . '/questions', $attributes)
            ->assertStatus(200);

        $this->assertDatabaseHas('questions', ['title' => 'First question']);
        $this->assertDatabaseHas('visibility_requirements', ['required_value' => $option->value]);
    }

    /** @test */
    public function a_user_cant_create_questions_to_another_users_form()
    {
        $this->withExceptionHandling();

        $form = create(Form::class, ['user_id' => 9999]);
        $attributes = raw(Question::class);

        $this->login()
            ->post(formPath($form) . '/questions', $attributes)
            ->assertStatus(403);
    }
}