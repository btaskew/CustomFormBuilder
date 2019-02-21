<?php

namespace Tests\Unit;

use App\Question;
use App\VisibilityRequirement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisibilityRequirementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_questions_visibility_requirement_is_deleted_when_the_question_is_deleted()
    {
        $question = create(Question::class);
        $requirement = create(VisibilityRequirement::class, ['question_id' => $question->id]);

        $question->delete();

        $this->assertDatabaseMissing('visibility_requirements', ['id' => $requirement->id]);
    }

    /** @test */
    public function a_visibility_requirement_is_deleted_if_its_required_question_is_deleted()
    {
        $form = $this->loginUserWithForm();
        $requiredQuestion = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);
        $requirement = create(VisibilityRequirement::class, ['required_question_id' => $requiredQuestion->id]);

        $requiredQuestion->delete();

        $this->assertDatabaseMissing('visibility_requirements', ['id' => $requirement->id]);
    }

    /** @test */
    public function a_visibility_requirement_is_deleted_if_its_required_question_type_is_changed()
    {
        $form = $this->loginUserWithForm();
        $requiredQuestion = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);
        $requirement = create(VisibilityRequirement::class, ['required_question_id' => $requiredQuestion->id]);

        $requiredQuestion->update(['type' => 'text']);

        $this->assertDatabaseMissing('visibility_requirements', ['id' => $requirement->id]);
    }
}
