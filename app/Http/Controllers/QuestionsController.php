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
            'form_title' => $form->title
        ]);
    }

    /**
     * @param Form    $form
     * @param Request $request
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Form $form, Request $request)
    {
        $this->authorize('createQuestion', $form);

        $form->questions()->create([
            'title' => $request->input('title'),
            'type' => $request->input('type')
        ]);
    }
}