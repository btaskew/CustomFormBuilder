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
     */
    public function show(Form $form)
    {
        return view('form.preview', [
            'form' => $form,
            'formView' => $form->build(),
            'description' => $form->description
        ]);
    }
}