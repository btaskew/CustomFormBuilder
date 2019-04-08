<?php

namespace Tests\Unit;

use App\Question;
use App\SelectOption;
use App\Specifications\CanSetVisibilityRequirement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CanSetVisibilityRequirementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function returns_true_if_can_set_visibility_requirement()
    {
        $question = create(Question::class);
        $requiredQuestion = create(Question::class, ['type' => 'radio', 'form_id' => $question->form_id]);
        $option = create(SelectOption::class, ['question_id' => $requiredQuestion]);

        $this->assertTrue(CanSetVisibilityRequirement::isSatisfiedBy($requiredQuestion->id, $option->value, $question));
    }

    /** @test */
    public function returns_false_when_required_question_is_same_as_question()
    {
        $question = create(Question::class);

        $this->assertFalse(CanSetVisibilityRequirement::isSatisfiedBy($question->id, 'value', $question));
    }

    /** @test */
    public function returns_false_when_required_question_doesnt_exist()
    {
        $question = create(Question::class);

        $this->assertFalse(CanSetVisibilityRequirement::isSatisfiedBy(999, 'value', $question));
    }

    /** @test */
    public function returns_false_if_required_question_belongs_to_different_form()
    {
        $question = create(Question::class);
        $requiredQuestion = create(Question::class, ['type' => 'radio', 'form_id' => 9999]);

        $this->assertFalse(CanSetVisibilityRequirement::isSatisfiedBy($requiredQuestion->id, 'value', $question));
    }

    /** @test */
    public function returns_false_when_required_value_doesnt_exist()
    {
        $question = create(Question::class);
        $requiredQuestion = create(Question::class, ['type' => 'radio', 'form_id' => $question->form_id]);

        $this->assertFalse(CanSetVisibilityRequirement::isSatisfiedBy($requiredQuestion->id, 'value', $question));
    }
}