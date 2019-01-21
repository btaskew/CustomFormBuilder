<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;

class QuestionSearchController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function show(Request $request)
    {
        $request->validate([
            'title' => 'string|required'
        ]);

        return Question::where('in_question_bank', true)
            ->where('title', 'like', '%' . $request->input('title') . '%')
            ->get();
    }
}