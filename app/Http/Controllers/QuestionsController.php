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

        $question = $form->questions()->create($request->only([
            'title', 'type', 'help_text', 'required', 'admin_only', 'order',
        ]));

        if ($question->isSelectQuestion()) {
            $question->addOptions($request->input('options'));
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

        $question->update($request->only([
            'title', 'type', 'help_text', 'required', 'admin_only', 'order',
        ]));

        if ($question->isSelectQuestion()) {
            $question->updateOptions($request->input('options'));
        } else {
            $question->options->each->delete();
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