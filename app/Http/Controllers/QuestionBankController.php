<?php

namespace App\Http\Controllers;

use App\Services\QuestionSetter;
use App\Form;
use App\Http\Requests\QuestionRequest;
use App\Question;
use Illuminate\Http\Request;

class QuestionBankController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $questions = Question::where('form_id', null)->where('in_question_bank', true)->with('options')->paginate(25);

        if ($request->route('form')) {
            return view('form.questionBank', [
                'questions' => $questions,
                'form' => Form::findOrFail($request->route('form'))
            ]);
        }

        return view('questionBank.index', [
            'questions' => $questions
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