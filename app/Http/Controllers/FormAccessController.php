<?php

namespace App\Http\Controllers;

use App\Form;
use App\FormUser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormAccessController extends Controller
{
    /**
     * @param Form $form
     * @return View
     * @throws AuthorizationException
     */
    public function index(Form $form)
    {
        $this->authorize('update', $form);

        return view('form.users', [
            'form' => $form,
            'users' => $form->usersWithAccess
        ]);
    }

    /**
     * @param Request $request
     * @param Form    $form
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(Request $request, Form $form)
    {
        $attributes = $request->validate([
            'username' => 'string|required|min:3',
            'access' => 'string|required|in:view,update'
        ]);

        $this->authorize('update', $form);

        return FormUser::createAccess(
            $attributes['username'],
            $attributes['access'],
            $form->id
        );
    }

    /**
     * @param Form     $form
     * @param FormUser $formUser
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Form $form, FormUser $formUser)
    {
        $this->authorize('update', $form);

        $formUser->delete();

        return response()->json(['success' => 'Access to form removed']);
    }
}
