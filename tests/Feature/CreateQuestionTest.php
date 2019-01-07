<?php

namespace Tests\Feature;

use App\Form;
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

        $form = factory(Form::class)->create(['user_id' => auth()->user()->id]);

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

        $form = factory(Form::class)->create(['user_id' => 9999]);

        $this->login()
            ->post('/forms/' . $form->id . '/questions', [])
            ->assertStatus(403);
    }
}