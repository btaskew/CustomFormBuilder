<?php

namespace App\Mappers;

use App\Question;
use App\SelectOption;
use Illuminate\Support\Collection;

class QuestionBankMapper
{
    /**
     * @param array $questions
     * @param int   $formId
     */
    public function addQuestions(array $questions, int $formId): void
    {
        foreach ($questions as $questionId) {
            $this->addQuestionToForm($formId, $questionId);
        }
    }

    /**
     * @param int $formId
     * @param int $questionId
     */
    private function addQuestionToForm(int $formId, int $questionId): void
    {
        $originalQuestion = Question::findOrFail($questionId);

        $question = $this->replicateQuestion($formId, $originalQuestion);

        if ($question->isSelectQuestion()) {
            $this->replicateOptions($originalQuestion->options, $question->id);
        }
    }

    /**
     * @param int      $formId
     * @param Question $originalQuestion
     * @return Question
     */
    private function replicateQuestion(int $formId, Question $originalQuestion): Question
    {
        $question = $originalQuestion->replicate(['in_question_bank', 'order']);
        $question->form_id = $formId;
        $question->in_question_bank = false;
        $question->setOrder();
        $question->save();
        return $question;
    }

    /**
     * @param Collection $options
     * @param int        $questionId
     */
    private function replicateOptions(Collection $options, int $questionId): void
    {
        $options->each(function (SelectOption $originalOption) use ($questionId) {
            $option = $originalOption->replicate(['question_id']);
            $option->question_id = $questionId;
            $option->save();
        });
    }
}