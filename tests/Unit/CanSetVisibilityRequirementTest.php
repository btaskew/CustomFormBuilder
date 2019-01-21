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
    public function throws_exception_when_required_question_doesnt_exist()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Required question does not exist");

        CanSetVisibilityRequirement::isSatisfiedBy([
            'question' => 999,
            'value' => 'value'
        ], 1);
    }

    /** @test */
    public function throws_exception_if_required_question_belongs_to_different_form()
    {
        $requiredQuestion = create(Question::class, ['type' => 'radio', 'form_id' => 9999]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Required question is on a different form");

        CanSetVisibilityRequirement::isSatisfiedBy([
            'question' => $requiredQuestion->id,
            'value' => 'value'
        ], 1);
    }

    /** @test */
    public function throws_exception_when_required_value_doesnt_exist()
    {
        $requiredQuestion = create(Question::class, ['type' => 'radio']);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Required value does not exist");

        CanSetVisibilityRequirement::isSatisfiedBy([
            'question' => $requiredQuestion->id,
            'value' => 'value'
        ], $requiredQuestion->form_id);
    }
}