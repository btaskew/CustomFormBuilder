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
        ]);
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
        ]);
    }
}