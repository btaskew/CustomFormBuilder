<?php

namespace Tests\Unit;

use App\Form;
use App\Question;
use App\SelectOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_question_has_a_form()
    {
        $form = create(Form::class);
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->assertEquals($form->id, $question->form->id);
    }

    /** @test */
    public function a_question_can_have_select_options()
    {
        $question = create(Question::class);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $this->assertEquals($option->id, $question->options->first()->id);
    }

    /** @test */
    public function a_question_knows_if_it_has_options()
    {
        $this->assertTrue((create(Question::class, ['type' => 'checkbox']))->isSelectQuestion());
        $this->assertTrue((create(Question::class, ['type' => 'radio']))->isSelectQuestion());
        $this->assertTrue((create(Question::class, ['type' => 'dropdown']))->isSelectQuestion());

        $this->assertFalse((create(Question::class, ['type' => 'text']))->isSelectQuestion());
    }
}