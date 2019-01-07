<?php

namespace App\Http\Controllers;

use App\Form;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    /**
     * @param Form $form
     * @return mixed
     */
    public function index(Form $form)
    {
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
}