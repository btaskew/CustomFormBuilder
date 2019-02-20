<?php

namespace Tests\Unit;

use App\Question;
use App\Specifications\CanSetVisibilityRequirement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CanSetVisibilityRequirementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function throws_exception_when_required_question_is_same_as_question()
    {
        $question = create(Question::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Can't set requirement against self");

        CanSetVisibilityRequirement::isSatisfiedBy([
            'question' => $question->id,
            'value' => 'value'
        ], $question);
    }

    /** @test */
    public function throws_exception_when_required_question_doesnt_exist()
    {
        $question = create(Question::class);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Required question does not exist");

        CanSetVisibilityRequirement::isSatisfiedBy([
            'question' => 999,
            'value' => 'value'
        ], $question);
    }

    /** @test */
    public function throws_exception_if_required_question_belongs_to_different_form()
    {
        $question = create(Question::class);
        $requiredQuestion = create(Question::class, ['type' => 'radio', 'form_id' => 9999]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Required question is on a different form");

        CanSetVisibilityRequirement::isSatisfiedBy([
            'question' => $requiredQuestion->id,
            'value' => 'value'
        ], $question);
    }

    /** @test */
    public function throws_exception_when_required_value_doesnt_exist()
    {
        $question = create(Question::class);
        $requiredQuestion = create(Question::class, ['type' => 'radio', 'form_id' => $question->form_id]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Required value does not exist");

        CanSetVisibilityRequirement::isSatisfiedBy([
            'question' => $requiredQuestion->id,
            'value' => 'value'
        ], $question);
    }
}