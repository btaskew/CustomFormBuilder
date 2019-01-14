<?php

namespace App\Http\Controllers;

use App\Facades\QuestionFacade;
use App\Form;
use App\Http\Requests\QuestionRequest;
use App\Question;

class QuestionsController extends Controller
{
    /**
     * @param Form $form
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Form $form)
    {
        $this->authorize('update', $form);

        $questions = $form->questions()->orderBy('order')->get(['id', 'title']);

        if (request()->wantsJson()) {
            return $questions;
        }

        return view('questions.index', [
            'questions' => $questions,
            'form' => $form
        ]);
    }

    /**
     * @param Form     $form
     * @param Question $question
     * @return Question
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Form $form, Question $question)
    {
        $this->authorize('update', $form);

        return $question->load(['options', 'visibilityRequirement']);
    }

    /**
     * @param Form $form
     * @return \Illuminate\View\View
     */
    public function create(Form $form)
    {
        return view('questions.create', compact('form'));
    }

    /**
     * @param Form            $form
     * @param QuestionRequest $request
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Form $form, QuestionRequest $request)
    {
        $this->authorize('update', $form);

        QuestionFacade::createQuestion($form, $request);
    }

    /**
     * @param Form            $form
     * @param Question        $question
     * @param QuestionRequest $request
     * @return Question
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update($form, Question $question, QuestionRequest $request)
    {
        $this->authorize('update', $question);

        QuestionFacade::updateQuestion($question, $request);

        return $question->fresh()->load(['options', 'visibilityRequirement']);
    }

    /**
     * @param Form     $form
     * @param Question $question
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($form, Question $question)
    {
        $this->authorize('update', $question);

        $question->delete();

        return response()->json(['success' => 'Question deleted']);
    }
}