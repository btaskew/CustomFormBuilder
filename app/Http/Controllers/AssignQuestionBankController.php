<?php

namespace App\Http\Controllers;

use App\Contracts\QuestionBankReplicator;
use App\Form;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssignQuestionBankController extends Controller
{
    /**
     * @var QuestionBankReplicator
     */
    private $questionBankReplicator;

    /**
     * @param QuestionBankReplicator $questionBankReplicator
     */
    public function __construct(QuestionBankReplicator $questionBankReplicator)
    {
        $this->questionBankReplicator = $questionBankReplicator;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $form = Form::findOrFail($request->input('form'));

        $this->authorize('update', $form);

        $this->questionBankReplicator::addQuestions($request->input('questions'), $form->id);

        return response()->json(['success' => 'Questions added to form']);
    }
}
