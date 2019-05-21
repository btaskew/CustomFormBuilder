<?php

namespace App\Http\Controllers;

use App\Form;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\View\View;

class FormPreviewController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param Form $form
     * @return View
     * @throws AuthorizationException
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
