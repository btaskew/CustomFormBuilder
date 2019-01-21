<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;

class QuestionSearchController extends Controller
{
    public function show(Request $request)
    {
        // TODO validate request title

        return Question::where('in_question_bank', true)
            ->where('title', 'like', '%' . $request->input('title') . '%')
            ->get();
    }
}