<?php

namespace App\Http\Controllers;

use App\Form;
use App\Services\QuestionBankReplicator;
use Illuminate\Http\Request;

class AssignQuestionBankController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $form = Form::findOrFail($request->input('form'));

        $this->authorize('edit', $form);

        (new QuestionBankReplicator())->addQuestions($request->input('questions'), $form->id);

        return response()->json(['success' => 'Questions added to form']);
    }
}
