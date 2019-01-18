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
}