<?php

namespace Tests\Unit;

use App\Form;
use App\Question;
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
}