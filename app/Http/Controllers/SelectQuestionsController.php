<?php

namespace App\Http\Controllers;

use App\Form;

class SelectQuestionsController extends Controller
{
    /**
     * @param Form $form
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Form $form)
    {
        $questions = $form->questions()
            ->select('id', 'title')
            ->whereIn('type', ['checkbox', 'radio', 'dropdown'])
            ->with('options:value,question_id')
            ->get();

        return response()->json(['data' => $questions->toArray()]);
    }
}