<?php

namespace Tests\Feature\Question;

use App\Question;
use App\SelectOption;
use App\VisibilityRequirement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageVisibilityRequirementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_add_a_visibility_requirement_to_an_existing_question()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => $form->id]);
        $requiredQuestion = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);
        $option = create(SelectOption::class, ['question_id' => $requiredQuestion->id]);

        $this->patch(
            '/forms/' . $question->form->id . '/questions/' . $question->id,
            [
                'title' => 'New title',
                'type' => 'text',
                'required_if' => [
                    'question' => $requiredQuestion->id,
                    'value' => $option->value
                ]
            ]
        )->assertStatus(200);

        $this->assertDatabaseHas('visibility_requirements', ['required_value' => $option->value]);
    }

    /** @test */
    public function a_user_can_edit_the_visibility_requirement_of_a_question()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => $form->id]);
        $requiredQuestion = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);
        $option = create(SelectOption::class, ['question_id' => $requiredQuestion->id]);
        $requirement = create(VisibilityRequirement::class, ['question_id' => $question->id]);

        $this->patch(
            '/forms/' . $question->form->id . '/questions/' . $question->id,
            [
                'title' => 'New title',
                'type' => 'text',
                'required_if' => [
                    'question' => $requiredQuestion->id,
                    'value' => $option->value
                ]
            ]
        )->assertStatus(200);

        $this->assertEquals($option->value, $requirement->fresh()->required_value);
    }

    /** @test */
    public function a_user_can_remove_a_visibility_requirement_from_a_question()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => $form->id]);
        create(VisibilityRequirement::class, ['question_id' => $question->id]);

        $this->patch(
            '/forms/' . $question->form->id . '/questions/' . $question->id,
            [
                'title' => 'New title',
                'type' => 'text',
                'required_if' => [
                    'question' => null,
                    'value' => null
                ]
            ]
        )->assertStatus(200);

        $this->assertDatabaseMissing('visibility_requirements', ['question_id' => $question->id]);
    }
}