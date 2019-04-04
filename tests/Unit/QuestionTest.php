<?php

namespace Tests\Unit;

use App\Form;
use App\Question;
use App\SelectOption;
use App\VisibilityRequirement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_question_has_a_form()
    {
        $form = create(Form::class);
        $question = make(Question::class, ['form_id' => $form->id]);

        $this->assertTrue($question->form->is($form));
    }

    /** @test */
    public function a_question_can_have_select_options()
    {
        $question = create(Question::class);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $this->assertTrue($question->options->first()->is($option));
    }

    /** @test */
    public function a_question_can_have_a_visibility_requirement()
    {
        $question = create(Question::class);
        $requirement = create(VisibilityRequirement::class, ['question_id' => $question->id]);

        $this->assertTrue($question->visibilityRequirement->is($requirement));
    }

    /** @test */
    public function a_question_can_have_visibility_requirement_dependants()
    {
        $question = create(Question::class);
        $requirement = create(VisibilityRequirement::class, ['required_question_id' => $question->id]);

        $this->assertTrue($question->visibilityRequirementDependants->first()->is($requirement));
    }

    /** @test */
    public function a_question_knows_if_it_has_options()
    {
        $this->assertTrue((make(Question::class, ['type' => 'checkbox']))->isSelectQuestion());
        $this->assertTrue((make(Question::class, ['type' => 'radio']))->isSelectQuestion());
        $this->assertTrue((make(Question::class, ['type' => 'dropdown']))->isSelectQuestion());

        $this->assertFalse((make(Question::class, ['type' => 'text']))->isSelectQuestion());
    }

    /** @test */
    public function a_question_can_create_a_new_select_option()
    {
        $question = create(Question::class);

        $question->setOptions([['id' => null, 'value' => 'value', 'display_value' => 'Display']]);

        $this->assertDatabaseHas('select_options', ['value' => 'value']);
    }

    /** @test */
    public function a_question_can_update_a_select_option()
    {
        $question = create(Question::class);
        $option = create(SelectOption::class, ['value' => 'old', 'question_id' => $question->id]);

        $question->setOptions([['id' => $option->id, 'value' => 'value', 'display_value' => 'Display']]);

        $this->assertEquals('value', $option->fresh()->value);
    }

    /** @test */
    public function a_question_can_create_and_update_an_option_at_the_same_time()
    {
        $question = create(Question::class);
        $option = create(SelectOption::class, ['value' => 'old', 'question_id' => $question->id]);

        $question->setOptions([
            ['id' => $option->id, 'value' => 'value', 'display_value' => 'Display'],
            ['id' => null, 'value' => 'value 2', 'display_value' => 'Display 2'],
        ]);

        $this->assertEquals('value', $option->fresh()->value);
        $this->assertDatabaseHas('select_options', ['value' => 'value 2']);
    }

    /** @test */
    public function the_order_value_is_calculated_when_the_model_is_created()
    {
        $form = create(Form::class);
        // Existing question
        create(Question::class, ['form_id' => $form->id, 'order' => 0]);

        $newQuestion = $form->questions()->create([
            'title' => 'Title', 'type' => 'text'
        ]);

        $this->assertEquals(1, $newQuestion->fresh()->order);
    }

    /** @test */
    public function the_order_value_is_set_to_zero_when_is_the_first_question()
    {
        $form = create(Form::class);

        $newQuestion = $form->questions()->create([
            'title' => 'Title', 'type' => 'text'
        ]);

        $this->assertEquals(0, $newQuestion->fresh()->order);
    }

    /** @test */
    public function a_question_can_set_a_visibility_requirement()
    {
        $question = create(Question::class, ['form_id' => 1]);
        $requiredQuestion = create(Question::class, ['type' => 'radio', 'form_id' => 1]);
        $option = create(SelectOption::class, ['question_id' => $requiredQuestion->id]);

        $question->setVisibilityRequirement($requiredQuestion->id, $option->value);

        $this->assertDatabaseHas('visibility_requirements', ['question_id' => $question->id]);
    }

    /** @test */
    public function a_question_can_get_its_full_unique_title()
    {
        $question = create(Question::class, [
            'order' => 0,
            'title' => 'Title'
        ]);

        $this->assertEquals('1. Title', $question->getFullTitle());
    }
}