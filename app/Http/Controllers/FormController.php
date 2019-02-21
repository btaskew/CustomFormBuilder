<?php

namespace App\Http\Controllers;

use App\Folder;
use App\Form;
use App\Http\Requests\FormRequest;

class FormController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'permission:manage_forms'])->except(['show']);
    }

    /**
     * Display a listing of the form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $forms = auth()->user()->getAllForms();
        $folders = $forms->load('folder:id,name')->groupBy('folder.name');

        return view('form.index', compact('folders'));
    }

    /**
     * Show the form for creating a new form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $folders = Folder::all();

        return view('form.create', compact('folders'));
    }

    /**
     * Store a newly created form in storage.
     *
     * @param FormRequest $request
     * @return Form
     */
    public function store(FormRequest $request)
    {
        return Form::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'open_date' => $request->input('open_date'),
            'close_date' => $request->input('close_date'),
            'active' => $request->has('active') ? $request->input('active') : false,
            'admin_email' => $request->input('admin_email'),
            'success_text' => $request->input('success_text'),
            'response_email' => $request->input('response_email'),
            'folder_id' => $request->input('folder_id'),
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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Form $form)
    {
        $this->authorize('edit', $form);

        $folders = Folder::all();

        return view('form.edit', compact('form', 'folders'));
    }

    /**
     * Update the specified form in storage.
     *
     * @param FormRequest $request
     * @param Form        $form
     * @return Form
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(FormRequest $request, Form $form)
    {
        $this->authorize('edit', $form);

        if ($request->has('response_email_field')
            &&
            !$form->questions->contains('id', $request->input('response_email_field'))
        ) {
            return response()->json(['error' => 'Question for the response email field not present on form'], 422);
        }

        $form->update($request->only([
            'title',
            'description',
            'open_date',
            'close_date',
            'active',
            'admin_email',
            'success_text',
            'response_email',
            'response_email_field',
            'folder_id'
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
        $this->authorize('edit', $form);

        $form->delete();

        return response()->json(['success' => 'Form deleted']);
    }
}
