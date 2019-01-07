<?php

namespace Tests\Feature;

use App\Form;
use App\Question;
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
        $this->login();
        $form = create(Form::class, ['user_id' => auth()->user()->id]);

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
    public function a_user_cant_create_questions_to_another_users_form()
    {
        $this->withExceptionHandling();

        $form = create(Form::class, ['user_id' => 9999]);

        $this->login()
            ->post('/forms/' . $form->id . '/questions', [])
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_edit_a_question()
    {
        $this->login();
        $form = create(Form::class, ['user_id' => auth()->user()->id]);
        $question = create(Question::class, ['title' => 'Old title', 'form_id' => $form->id]);

        $this->patch(
            '/forms/' . $question->form->id . '/questions/' . $question->id,
            ['title' => 'New title', 'type' => 'type']
        )->assertStatus(200);

        $this->assertEquals('New title', $question->fresh()->title);
    }

    /** @test */
    public function a_user_cant_edit_questions_that_belong_to_another_users_form()
    {
        $this->withExceptionHandling();

        $form = create(Form::class, ['user_id' => 9999]);
        $question = create(Question::class, ['title' => 'Old title', 'form_id' => $form->id]);

        $this->login()
            ->patch('/forms/' . $form->id . '/questions/' . $question->id, [])
            ->assertStatus(403);

        $this->assertEquals('Old title', $question->fresh()->title);
    }
}