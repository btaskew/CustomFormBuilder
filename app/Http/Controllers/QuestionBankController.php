<?php

namespace App\Http\Controllers;

use App\Form;
use App\Mappers\QuestionBankMapper;
use App\Question;
use App\SelectOption;
use Illuminate\Http\Request;

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

    /**
     * @param Form    $form
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Form $form, Request $request)
    {
        $this->authorize('update', $form);

        (new QuestionBankMapper())->map($request->input('questions'), $form->id);

        return response()->json(['success' => 'Questions added to form']);
    }
}