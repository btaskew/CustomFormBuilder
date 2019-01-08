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
        $this->authorize('createQuestion', $form);

        $questions = $form->questions()->get(['id', 'title']);

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
        $this->authorize('createQuestion', $form);

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
        $this->authorize('createQuestion', $form);

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
        }

        return $question;
    }
}