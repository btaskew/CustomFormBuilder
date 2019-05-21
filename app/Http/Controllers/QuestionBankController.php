<?php

namespace App\Http\Controllers;

use App\Contracts\QuestionSetter;
use App\Form;
use App\Http\Requests\QuestionRequest;
use App\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuestionBankController extends Controller
{
    /**
     * @var QuestionSetter
     */
    private $questionSetter;

    /**
     * @param QuestionSetter $questionSetter
     */
    public function __construct(QuestionSetter $questionSetter)
    {
        $this->questionSetter = $questionSetter;
    }

    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $questions = Question::where(['form_id' => null, 'in_question_bank' => true])->with('options')->paginate(25);

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
     * @return View
     */
    public function create()
    {
        return view('questionBank.create');
    }

    /**
     * @param QuestionRequest $request
     * @return JsonResponse
     */
    public function store(QuestionRequest $request)
    {
        $this->questionSetter::setBankQuestion($request);

        return response()->json(['success' => 'Question created']);
    }

    /**
     * @param Question $question
     * @return View
     */
    public function edit(Question $question)
    {
        $question->load('options');

        return view('questionBank.edit', compact('question'));
    }

    /**
     * @param Question        $question
     * @param QuestionRequest $request
     * @return Question|JsonResponse
     */
    public function update(Question $question, QuestionRequest $request)
    {
        if ($question->in_question_bank == false || $question->form_id != 0) {
            return response()->json(['error' => 'Trying to update question not in question bank'], 403);
        }

        $this->questionSetter::setBankQuestion($request, $question->id);

        return $question->fresh()->load(['options']);
    }
}
