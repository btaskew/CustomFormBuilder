<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class QuestionSearchController extends Controller
{
    /**
     * @param Request $request
     * @return Collection
     */
    public function show(Request $request)
    {
        $title = $request->validate(['title' => 'string|required'])['title'];

        return Question::where('in_question_bank', true)
            ->where('title', 'like', '%' . filter_var($title, FILTER_SANITIZE_STRING) . '%')
            ->limit(25)
            ->get();
    }
}