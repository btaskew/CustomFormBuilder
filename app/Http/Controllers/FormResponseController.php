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
        $paginatedResponses = $form->responses()->paginate(25);
        $questions = $form->getOrderedQuestions()->reject(function ($question) {
            return $question->type == 'label';
        });

        return view('responses.index', [
            'responses' => (new ResponseMapper($form, $questions))->map(collect($paginatedResponses->items())),
            'paginatedResponses' => $paginatedResponses,
            'form' => $form,
            'questions' => $questions
        ]);
    }

    /**
     * @param Form    $form
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Form $form, Request $request)
    {
        if (!$form->isActive()) {
            return response()->json(['error' => 'This form is not currently accepting responses'], 403);
        }

        $responseData = [];

        $form->questions->reject(function ($question) {
            return $question->type == 'label';
        })->each(function (Question $question) use ($request, &$responseData) {
            $responseData[$question->id] = $request->input($question->id);
        });

        $response = $form->responses()->create([
            'response' => json_encode($responseData)
        ]);

        event(new ResponseRecorded($response));

        return response()->json(['success' => 'Response stored']);
    }
}