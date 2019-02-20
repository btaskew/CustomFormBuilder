<?php

namespace App\Http\Controllers;

use App\Form;
use Illuminate\Http\Request;

class FormOrderController extends Controller
{
    /**
     * @param Form    $form
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Form $form, Request $request)
    {
        $this->authorize('edit', $form);

        foreach ($request->input('order') as $order) {
            $form->questions()
                ->where('id', $order['question'])
                ->update(['order' => $order['order']]);
        }

        return response()->json(['success' => 'Order updated']);
    }
}