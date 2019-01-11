<?php

namespace App\Http\Controllers;

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

        return $question;
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

        //TODO wrap these in a transaction, probably in a facade
        $question = $form->questions()->create($request->only([
            'title', 'type', 'help_text', 'required', 'admin_only'
        ]));

        if ($question->isSelectQuestion()) {
            $question->setOptions($request->input('options'));
        }

        if ($request->has('required_if')) {
            $question->setVisibilityRequirement($request->input('required_if'));
        }
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

        //TODO wrap these in a transaction, probably in a facade
        $question->update($request->only([
            'title', 'type', 'help_text', 'required', 'admin_only'
        ]));

        if ($question->isSelectQuestion()) {
            $question->setOptions($request->input('options'));
        } else {
            $question->options->each->delete();
        }

        if ($request->has('required_if') && !is_null($request->input('required_if')['question'])) {
            $question->setVisibilityRequirement($request->input('required_if'));
        } else if ($question->visibilityRequirement()->exists()) {
            $question->visibilityRequirement->delete();
        }

        return $question->fresh();
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