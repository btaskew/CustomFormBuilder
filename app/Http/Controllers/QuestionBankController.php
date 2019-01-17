<?php

namespace App\Http\Controllers;

use App\Form;
use App\Question;

class QuestionBankController extends Controller
{
    public function index(Form $form)
    {
        return view('questionBank.index', [
            'questions' => Question::where('form_id', null)->where('in_question_bank', true)->with('options')->get(),
            'form' => $form
        ]);
    }
}