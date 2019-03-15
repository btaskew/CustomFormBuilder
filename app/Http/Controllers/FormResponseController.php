<?php

namespace App\Http\Controllers;

use App\Contracts\ResponseFormatter;
use App\Form;
use App\Http\Requests\ResponseRequest;

class FormResponseController extends Controller
{
    /**
     * @var ResponseFormatter
     */
    private $responseFormatter;

    /**
     * @param ResponseFormatter $responseFormatter
     */
    public function __construct(ResponseFormatter $responseFormatter)
    {
        $this->responseFormatter = $responseFormatter;
    }

    /**
     * @param Form $form
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Form $form)
    {
        $this->authorize('view', $form);

        $paginatedResponses = $form->responses()->paginate(25);
        $questions = $form->getAnswerableQuestions();
        $responses = $this->responseFormatter
            ->setQuestions($questions)
            ->formatResponses($paginatedResponses->items());

        return view('responses.index', [
            'responses' => $responses,
            'pagination' => $paginatedResponses,
            'form' => $form,
            'questions' => $questions
        ]);
    }

    /**
     * @param Form            $form
     * @param ResponseRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Form $form, ResponseRequest $request)
    {
        if (!$form->isActive()) {
            return response()->json(['error' => 'This form is not currently accepting responses'], 403);
        }

        $form->recordResponse($request);

        return response()->json(['success' => 'Response stored']);
    }
}