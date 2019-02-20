<?php

namespace App\Http\Controllers;

use App\Form;

class FormPreviewController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param Form $form
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Form $form)
    {
        $this->authorize('view', $form);

        return view('form.preview', [
            'form' => $form,
            'questions' => $form->getOrderedQuestions(),
            'description' => $form->description
        ]);
    }
}