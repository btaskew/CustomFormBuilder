<?php

namespace App\Http\Controllers;

use App\Contracts\FormResponder;
use App\Contracts\ResponseFormatter;
use App\Form;
use App\Http\Requests\ResponseRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class FormResponseController extends Controller
{
    /**
     * @var ResponseFormatter
     */
    private $responseFormatter;

    /**
     * @var FormResponder
     */
    private $formResponder;

    /**
     * @param ResponseFormatter $responseFormatter
     * @param FormResponder     $formResponder
     */
    public function __construct(ResponseFormatter $responseFormatter, FormResponder $formResponder)
    {
        $this->responseFormatter = $responseFormatter;
        $this->formResponder = $formResponder;
    }

    /**
     * @param Form $form
     * @return View
     * @throws AuthorizationException
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
     * @return JsonResponse
     */
    public function store(Form $form, ResponseRequest $request)
    {
        if (!$form->isActive()) {
            return response()->json(['error' => 'This form is not currently accepting responses'], 403);
        }

        $this->formResponder->saveResponse($request, $form);

        return response()->json(['success' => 'Response stored']);
    }
}
