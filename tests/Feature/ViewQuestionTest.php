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
        $this->login();
        $form = create(Form::class, ['user_id' => auth()->user()->id]);
        $question = create(Question::class, ['title' => 'Old title', 'form_id' => $form->id]);

        $this->get('/forms/' . $form->id . '/questions/' . $question->id)
            ->assertStatus(200)
            ->assertSee($question->title);
    }
}