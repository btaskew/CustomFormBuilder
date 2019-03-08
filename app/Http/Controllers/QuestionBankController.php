<?php

namespace App\Http\Controllers;

use App\Services\QuestionSetter;
use App\Form;
use App\Http\Requests\QuestionRequest;
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

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('questionBank.create');
    }

    /**
     * @param QuestionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(QuestionRequest $request)
    {
        QuestionSetter::setBankQuestion($request);

        return response()->json(['success' => 'Question created']);
    }

    /**
     * @param Question        $question
     * @param QuestionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Question $question, QuestionRequest $request)
    {
        if ($question->in_question_bank == false || $question->form_id != 0) {
            return response()->json(['error' => 'Trying to update question not in question bank'], 403);
        }

        QuestionSetter::setBankQuestion($request, $question->id);

        return response()->json(['success' => 'Question updated']);
    }
}