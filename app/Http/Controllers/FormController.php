<?php

namespace App\Http\Controllers;

use App\Form;
use App\Rules\EmailList;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show']);
    }

    /**
     * Display a listing of the form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('form.index', ['forms' => auth()->user()->forms]);
    }

    /**
     * Show the form for creating a new form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('form.create');
    }

    /**
     * Store a newly created form in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Form
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'string|required',
            'description' => 'string|nullable',
            'open_date' => 'date',
            'close_date' => 'date',
            'admin_email' => ['string', new EmailList()],
            'active' => 'boolean',
            'success_text' => 'string|nullable',
            'response_email' => 'string|nullable',
        ]);

        return Form::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'open_date' => $request->input('open_date'),
            'close_date' => $request->input('close_date'),
            'active' => $request->has('active') ? $request->input('active') : false,
            'admin_email' => $request->input('admin_email'),
            'success_text' => $request->input('success_text'),
            'response_email' => $request->input('response_email'),
            'user_id' => auth()->user()->id
        ]);
    }

    /**
     * Display the specified form.
     *
     * @param Form $form
     * @return \Illuminate\View\View
     */
    public function show(Form $form)
    {
        if (!$form->isActive()) {
            return view('form.inactive');
        }

        return view('form.show', [
            'form' => $form,
            'questions' => $form->getOrderedQuestions(),
            'description' => $form->description
        ]);
    }

    /**
     * Show the form for editing the specified form.
     *
     * @param Form $form
     * @return \Illuminate\View\View
     */
    public function edit(Form $form)
    {
        return view('form.edit', compact('form'));
    }

    /**
     * Update the specified form in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Form                      $form
     * @return Form
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Form $form)
    {
        $this->authorize('update', $form);

        if ($request->has('response_email_field')
            &&
            !$form->questions->contains('id', $request->input('response_email_field'))
        ) {
            return response()->json(['error' => 'Question for the response email field not present on form'], 422);
        }

        $form->update($request->validate([
            'title' => 'string|required',
            'description' => 'string|nullable',
            'open_date' => 'date',
            'close_date' => 'date',
            'active' => 'boolean',
            'admin_email' => ['string', new EmailList()],
            'success_text' => 'string|nullable',
            'response_email' => 'string|nullable',
            'response_email_field' => 'integer|nullable',
        ]));

        return $form;
    }

    /**
     * Remove the specified form from storage.
     *
     * @param Form $form
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Form $form)
    {
        $this->authorize('update', $form);

        $form->delete();

        return response()->json(['success' => 'Form deleted']);
    }
}
