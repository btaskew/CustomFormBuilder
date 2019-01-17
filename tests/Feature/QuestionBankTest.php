<?php

namespace Tests\Feature;

use App\Question;
use App\SelectOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionBankTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_all_the_questions_in_the_question_bank()
    {
        $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => null, 'in_question_bank' => true]);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $this->get('/forms/1/questions/bank')
            ->assertStatus(200)
            ->assertSee($question->title)
            ->assertSee($option->value);
    }

    /** @test */
    public function a_user_can_add_a_question_bank_question_to_their_form()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => null, 'in_question_bank' => true]);

        $this->post('/forms/' . $form->id . '/questions/bank', ['questions' => [$question->id]])
            ->assertStatus(200);

        $this->assertTrue($form->questions->contains('title', $question->title));
    }
}