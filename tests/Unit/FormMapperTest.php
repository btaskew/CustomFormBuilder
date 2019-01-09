<?php

namespace Tests\Unit;

use App\Mappers\FormMapper;
use App\Question;
use App\SelectOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Kris\LaravelFormBuilder\Form;
use Tests\TestCase;

class FormMapperTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function correctly_maps_a_forms_questions()
    {
        $question = create(Question::class);

        $form = \Mockery::mock(Form::class);
        $form->shouldReceive('add')->once()->with(
            $question->id,
            $question->type,
            [
                'label' => $question->title,
                'help_block' => ['text' => $question->help_text]
            ]
        );

        (new FormMapper($form))->mapQuestions(collect([$question]));
    }

    /** @test */
    public function correctly_maps_a_forms_questions_for_checkbox_questions()
    {
        $question = create(Question::class, ['type' => 'checkbox']);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $form = \Mockery::mock(Form::class);
        $form->shouldReceive('add')->once()->with(
            $question->id,
            'choice',
            [
                'label' => $question->title,
                'help_block' => ['text' => $question->help_text],
                'choices' => [$option->value => $option->display_value],
                'expanded' => true,
                'multiple' => true,
            ]
        );

        (new FormMapper($form))->mapQuestions(collect([$question]));
    }

    /** @test */
    public function correctly_maps_a_forms_questions_for_radio_button_questions()
    {
        $question = create(Question::class, ['type' => 'radio']);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $form = \Mockery::mock(Form::class);
        $form->shouldReceive('add')->once()->with(
            $question->id,
            'choice',
            [
                'label' => $question->title,
                'help_block' => ['text' => $question->help_text],
                'choices' => [$option->value => $option->display_value],
                'expanded' => true,
                'multiple' => false,
            ]
        );

        (new FormMapper($form))->mapQuestions(collect([$question]));
    }

    /** @test */
    public function correctly_maps_a_forms_questions_for_dropdown_questions()
    {
        $question = create(Question::class, ['type' => 'dropdown']);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $form = \Mockery::mock(Form::class);
        $form->shouldReceive('add')->once()->with(
            $question->id,
            'choice',
            [
                'label' => $question->title,
                'help_block' => ['text' => $question->help_text],
                'choices' => [$option->value => $option->display_value],
                'expanded' => false,
                'multiple' => false,
            ]
        );

        (new FormMapper($form))->mapQuestions(collect([$question]));
    }
}