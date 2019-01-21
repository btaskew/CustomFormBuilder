<?php

namespace App\Exports;

use App\Form;
use App\Mappers\ResponseMapper;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ResponsesExport implements FromView
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
     * @return View
     */
    public function view(): View
    {
        $questions = $this->form->getAnswerableQuestions();
        $responses = (new ResponseMapper($this->form, $questions))->map($this->form->responses);

        return view('responses._responseTable', [
            'responses' => $responses,
            'questions' => $questions
        ]);
    }
}
