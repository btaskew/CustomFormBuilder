<?php

namespace App\Http\Controllers;

use App\Form;
use App\Question;
use Illuminate\Http\Request;

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

        $questions = $form->questions;

        if (request()->wantsJson()) {
            return $questions;
        }

        return view('questions.index', [
            'questions' => $questions,
            'form' => $form
        ]);
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
     * @param Form    $form
     * @param Request $request
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Form $form, Request $request)
    {
        $this->authorize('createQuestion', $form);

        $form->questions()->create($request->only([
            'title',
            'type',
            'help_text',
            'required' ,
            'admin_only',
            'order'
        ]));
    }

    /**
     * @param Form     $form
     * @param Question $question
     * @param Request  $request
     * @return Question
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update($form, Question $question, Request $request)
    {
        $this->authorize('update', $question);

        $question->update($request->only([
            'title',
            'type',
            'help_text',
            'required' ,
            'admin_only',
            'order'
        ]));

        return $question;
    }
}