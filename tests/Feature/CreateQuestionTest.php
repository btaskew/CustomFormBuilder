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
}