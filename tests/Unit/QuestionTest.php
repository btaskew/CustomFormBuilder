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
    public function a_question_can_have_a_visibility_requirement()
    {
        $question = create(Question::class);
        $option = create(VisibilityRequirement::class, ['question_id' => $question->id]);

        $this->assertEquals($option->id, $question->visibilityRequirement->first()->id);
    }

    /** @test */
    public function a_question_knows_if_it_has_options()
    {
        $this->assertTrue((create(Question::class, ['type' => 'checkbox']))->isSelectQuestion());
        $this->assertTrue((create(Question::class, ['type' => 'radio']))->isSelectQuestion());
        $this->assertTrue((create(Question::class, ['type' => 'dropdown']))->isSelectQuestion());

        $this->assertFalse((create(Question::class, ['type' => 'text']))->isSelectQuestion());
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
    public function a_questions_select_options_are_deleted_when_the_question_is_deleted()
    {
        $question = create(Question::class);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $question->delete();

        $this->assertDatabaseMissing('select_options', ['id' => $option->id]);
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
        $question = create(Question::class);
        $requiredQuestion = create(Question::class, ['type' => 'radio']);
        $option = create(SelectOption::class, ['question_id' => $requiredQuestion->id]);

        $question->setVisibilityRequirement([
            'question' => $requiredQuestion->id,
            'value' => $option->value
        ]);

        $this->assertDatabaseHas('visibility_requirements', ['question_id' => $question->id]);
    }
}