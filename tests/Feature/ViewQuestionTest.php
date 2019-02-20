<?php

namespace Tests\Feature;

use App\Form;
use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewQuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_question_data_for_one_of_their_forms()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['title' => 'Old title', 'form_id' => $form->id]);

        $this->get(formPath($form) . '/questions/' . $question->id)
            ->assertStatus(200)
            ->assertSee($question->title);
    }

    /** @test */
    public function a_user_cant_view_question_data_for_someone_elses_form()
    {
        $this->withExceptionHandling();

        $this->login();
        $form = create(Form::class, ['user_id' => 999]);
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->get(formPath($form) . '/questions/' . $question->id)
            ->assertStatus(403);
    }
}