<?php

namespace Tests\Feature;

use App\Form;
use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_all_their_forms()
    {
        $this->login();
        $form = create(Form::class, ['user_id' => auth()->user()->id]);

        $this->json('get', '/forms')
            ->assertStatus(200)
            ->assertSee($form->title);
    }

    /** @test */
    public function a_user_can_view_a_forms_questions()
    {
        $this->login();
        $form = create(Form::class, ['user_id' => auth()->user()->id]);
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->json('get', '/forms/' . $form->id . '/questions')
            ->assertStatus(200)
            ->assertSee($question->title);
    }

    /** @test */
    public function a_user_cant_view_questions_on_another_users_form()
    {
        $this->withExceptionHandling();

        $this->login();
        $form = create(Form::class, ['user_id' => 999]);
        create(Question::class, ['form_id' => $form->id]);

        $this->get('/forms/' . $form->id . '/questions')
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_view_their_complete_form()
    {
        $this->login();
        $form = create(Form::class, ['user_id' => auth()->user()->id]);
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->get('/forms/' . $form->id)
            ->assertStatus(200)
            ->assertSee($form->title)
            ->assertSee($question->title);
    }
}