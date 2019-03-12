<?php

namespace App\Http\Controllers;

use App\Form;
use App\FormUser;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class FormAccessController extends Controller
{
    /**
     * @param Form $form
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, Form $form)
    {
        $request->validate([
            'username' => 'string|required',
            'access' => 'string|required|in:view,update'
        ]);

        $this->authorize('update', $form);

        return FormUser::createAccess(
            $request->input('username'),
            $request->input('access'),
            $form->id
        );
    }

    /**
     * @param Form     $form
     * @param FormUser $formUser
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Form $form, FormUser $formUser)
    {
        $this->authorize('update', $form);

        $formUser->delete();

        return response()->json(['success' => 'Access to form removed']);
    }
}
