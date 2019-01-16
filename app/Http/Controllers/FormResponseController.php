<?php

namespace App\Http\Controllers;

use App\Events\ResponseRecorded;
use App\Form;
use App\Mappers\ResponseMapper;
use App\Question;
use Illuminate\Http\Request;

class FormResponseController extends Controller
{
    /**
     * @param Form $form
     * @return \Illuminate\View\View
     */
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

        $response = $form->responses()->create([
            'response' => json_encode($response)
        ]);

        event(new ResponseRecorded($response));

        return response()->json(['success' => 'Response stored']);
    }
}