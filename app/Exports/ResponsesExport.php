<?php

namespace App\Exports;

use App\Contracts\ResponseFormatter;
use App\Form;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ResponsesExport implements FromView
{
    /**
     * @var Form
     */
    private $form;

    /**
     * @var ResponseFormatter
     */
    private $formatter;

    /**
     * @param Form              $form
     * @param ResponseFormatter $formatter
     */
    public function __construct(Form $form, ResponseFormatter $formatter)
    {
        $this->form = $form;
        $this->formatter = $formatter;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $questions = $this->form->getAnswerableQuestions();
        $responses = $this->formatter->setQuestions($questions)->formatResponses($this->form->responses);

        return view('responses._responseTable', [
            'responses' => $responses,
            'questions' => $questions
        ]);
    }
}
