<?php

namespace App\Http\Controllers;

use App\Form;
use App\Question;
use Illuminate\Http\Request;

class FormResponseController extends Controller
{
    public function index(Form $form)
    {
        return view('responses.index', [
            'form' => $form->load(["responses", "questions:id,title,form_id"])
        ]);
    }

    /**
     * @param Form    $form
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Form $form, Request $request)
    {
        $response = [];
        $form->questions->each(function (Question $question) use ($request, &$response) {
            $response[$question->id] = $request->input($question->id);
        });

        $form->responses()->create([
            'response' => json_encode($response)
        ]);

        return response()->json(['success' => 'Response stored']);
    }
}