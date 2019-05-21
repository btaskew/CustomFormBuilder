<?php

namespace App\Http\Controllers;

use App\Form;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SelectQuestionsController extends Controller
{
    /**
     * @param Request $request
     * @param Form    $form
     * @return Collection
     * @throws AuthorizationException
     */
    public function index(Request $request, Form $form)
    {
        $this->authorize('update', $form);

        $questions = $form->questions()
            ->select('id', 'title')
            ->whereIn('type', ['checkbox', 'radio', 'dropdown'])
            ->with('options:value,question_id')
            ->get();

        if ($request->has('exclude_question')) {
            $questions = $questions->except($request->input('exclude_question'));
        }

        return $questions;
    }
}
