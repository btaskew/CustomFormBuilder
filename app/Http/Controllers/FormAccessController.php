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
        $this->authorize('edit', $form);

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
            'access' => 'string|required|in:view,edit'
        ]);

        $this->authorize('edit', $form);

        if ($request->input('username') == auth()->user()->username) {
            return response()->json(['error' => "Can't grant access to self"], 422);
        }

        try {
            $user = User::where('username', $request->input('username'))->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Given user was not found in the database'], 404);
        }

        $user->pivot = FormUser::create([
            'user_id' => $user->id,
            'form_id' => $form->id,
            'access' => $request->input('access')
        ]);

        return $user;
    }

    /**
     * @param Form     $form
     * @param FormUser $formUser
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Form $form, FormUser $formUser)
    {
        $this->authorize('edit', $form);

        $formUser->delete();

        return response()->json(['success' => 'Access to form removed']);
    }
}
