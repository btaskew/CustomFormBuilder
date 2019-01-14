<?php

namespace App\Http\Controllers;

use App\Form;
use App\FormResponse;
use App\Question;
use Illuminate\Http\Request;

class FormResponseController extends Controller
{
    public function store(Form $form, Request $request)
    {
        $response = [];
        $form->questions->each(function (Question $question) use ($request, &$response) {
            $response[$question->id] = $request->input($question->id);
        });

        FormResponse::create([
            'form_id' => $form->id,
            'response' => json_encode($response)
        ]);
    }
}