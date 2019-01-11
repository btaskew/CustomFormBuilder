<?php

namespace App\Http\Controllers;

use App\Form;

class SelectQuestionsController extends Controller
{
    /**
     * @param Form $form
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(Form $form)
    {
        return $form->questions()
            ->select('id', 'title')
            ->whereIn('type', ['checkbox', 'radio', 'dropdown'])
            ->with('options:value,question_id')
            ->get();
    }
}