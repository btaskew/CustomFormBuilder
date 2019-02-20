<?php

namespace Tests\Feature;

use App\Question;
use App\SelectOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewSelectQuestionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_all_the_select_questions_on_their_form()
    {
        $form = $this->loginUserWithForm();
        $selectQuestion = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);
        $option = create(SelectOption::class, ['question_id' => $selectQuestion->id]);
        $otherQuestion = create(Question::class, ['type' => 'text', 'form_id' => $form->id]);

        $this->get(formPath($form) . '/select-questions')
            ->assertStatus(200)
            ->assertSee($selectQuestion->title)
            ->assertSee($option->value)
            ->assertDontSee($otherQuestion->title);
    }
}