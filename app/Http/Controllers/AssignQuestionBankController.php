<?php

namespace App\Http\Controllers;

use App\Form;
use App\Mappers\QuestionBankMapper;
use Illuminate\Http\Request;

class AssignQuestionBankController extends Controller
{
    /**
     * @param Form    $form
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Form $form, Request $request)
    {
        $this->authorize('edit', $form);

        (new QuestionBankMapper())->map($request->input('questions'), $form->id);

        return response()->json(['success' => 'Questions added to form']);
    }
}
