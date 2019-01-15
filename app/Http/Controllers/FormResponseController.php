<?php

namespace App\Http\Controllers;

use App\Form;
use App\Mappers\ResponseMapper;
use App\Question;
use Illuminate\Http\Request;

class FormResponseController extends Controller
{
    public function index(Form $form)
    {
        return view('responses.index', [
            'responses' => (new ResponseMapper($form))->map(),
            'form' => $form,
            'questions' => $form->getOrderedQuestions()
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