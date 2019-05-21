<?php

namespace App\Http\Controllers;

use App\SelectOption;
use Exception;
use Illuminate\Http\JsonResponse;

class SelectOptionsController extends Controller
{
    /**
     * @param SelectOption $option
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(SelectOption $option)
    {
        $this->authorize('update', $option);

        $option->delete();

        return response()->json(['success' => 'Option deleted']);
    }
}
