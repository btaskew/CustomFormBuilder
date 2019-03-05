<?php

namespace App\Http\Controllers;

use App\Form;
use App\Question;

class QuestionBankController extends Controller
{
    /**
     * @param Form $form
     * @return \Illuminate\View\View
     */
    public function index(Form $form)
    {
        $questions = Question::where('form_id', null)->where('in_question_bank', true)->with('options')->paginate(25);

        return view('questionBank.index', [
            'questions' => $questions,
            'form' => $form
        ]);
    }
}