<?php

namespace App\Http\Controllers;

use App\Form;
use App\Question;
use App\SelectOption;

class SelectOptionsController extends Controller
{
    /**
     * @param Form         $form
     * @param Question     $question
     * @param SelectOption $option
     * @throws \Exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($form, $question, SelectOption $option)
    {
        $this->authorize('update', $option);

        $option->delete();

        return response()->json(['success' => 'Option deleted']);
    }
}