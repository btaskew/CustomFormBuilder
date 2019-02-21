<?php

namespace Tests\Unit;

use App\Question;
use App\SelectOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SelectOptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_select_option_belongs_to_a_question()
    {
        $question = create(Question::class);
        $option = make(SelectOption::class, ['question_id' => $question->id]);

        $this->assertTrue($option->question->is($question));
    }

    /** @test */
    public function a_questions_select_options_are_deleted_when_the_question_is_deleted()
    {
        $question = create(Question::class);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $question->delete();

        $this->assertDatabaseMissing('select_options', ['id' => $option->id]);
    }

    /** @test */
    public function a_questions_select_options_are_deleted_when_the_type_is_changed()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $question->update(['type' => 'text']);

        $this->assertDatabaseMissing('select_options', ['id' => $option->id]);
    }
}