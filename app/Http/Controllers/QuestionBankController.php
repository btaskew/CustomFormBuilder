<?php

namespace App\Http\Controllers;

use App\Form;
use App\Question;
use Illuminate\Http\Request;

class QuestionBankController extends Controller
{
    /**
     * @param Form $form
     * @return \Illuminate\View\View
     */
    public function index(Form $form)
    {
        return view('questionBank.index', [
            'questions' => Question::where('form_id', null)->where('in_question_bank', true)->with('options')->get(),
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

        foreach ($request->input('questions') as $questionId) {
            $question = Question::findOrFail($questionId)->replicate();
            $question->form_id = $form->id;
            $question->in_question_bank = false;
            $question->setOrder();
            $question->save();
        }

        return response()->json(['success' => 'Questions added to form']);
    }
}