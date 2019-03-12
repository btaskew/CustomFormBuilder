<?php

namespace App\Http\Controllers;

use App\SelectOption;

class SelectOptionsController extends Controller
{
    /**
     * @param SelectOption $option
     * @throws \Exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(SelectOption $option)
    {
        $this->authorize('update', $option);

        $option->delete();

        return response()->json(['success' => 'Option deleted']);
    }
}