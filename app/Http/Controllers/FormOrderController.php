<?php

namespace App\Http\Controllers;

use App\Form;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormOrderController extends Controller
{
    /**
     * @param Form    $form
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(Form $form, Request $request)
    {
        $this->authorize('update', $form);

        foreach ($request->input('order') as $order) {
            $form->questions()
                ->where('id', $order['question'])
                ->update(['order' => $order['order']]);
        }

        return response()->json(['success' => 'Order updated']);
    }
}
