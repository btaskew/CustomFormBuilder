<?php

namespace App\Mappers;

use App\Question;
use App\SelectOption;
use Illuminate\Support\Collection;
use Kris\LaravelFormBuilder\Form;

class FormMapper
{
    /**
     * @var Form
     */
    private $form;

    /**
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    /**
     * @param Collection $questions
     * @return Form
     */
    public function mapQuestions(Collection $questions): Form
    {
        foreach ($questions as $question) {
            if ($question->isSelectQuestion()) {
                $this->addSelectQuestion($question);
                continue;
            }

            //TODO extract to method
            $this->form->add($question->id, $question->type, [
                'label' => $question->title,
                'help_block' => ['text' => $question->help_text]
            ]);
        }

        return $this->form;
    }

    /**
     * @param Question $question
     */
    private function addSelectQuestion(Question $question): void
    {
        $options = [
            'label' => $question->title,
            'help_block' => ['text' => $question->help_text],
            'choices' => $this->mapOptions($question->options)
        ];

        $this->calculateSelectType($options, $question->type);

        //TODO move to extracted method
        $this->form->add($question->id, 'choice', $options);
    }

    /**
     * @param Collection $options
     * @return array
     */
    private function mapOptions(Collection $options): array
    {
        $choices = [];

        $options->each(function (SelectOption $option) use (&$choices) {
            $choices[$option->value] = $option->display_value;
        });

        return $choices;
    }

    /**
     * @param array  $options
     * @param string $type
     */
    private function calculateSelectType(array &$options, string $type)
    {
        switch ($type) {
            case 'checkbox':
                $options['expanded'] = true;
                $options['multiple'] = true;
                break;
            case 'radio':
                $options['expanded'] = true;
                $options['multiple'] = false;
                break;
            case 'select':
                $options['expanded'] = false;
                $options['multiple'] = false;
                break;
            default:
                break;
        }
    }
}