<?php

namespace App\Http\Controllers;

use App\Folder;
use App\Form;
use App\Http\Requests\FormRequest;
use App\Specifications\CanSetResponseEmailField;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class FormController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth'])->except(['show']);
    }

    /**
     * @return Response
     */
    public function index()
    {
        $forms = auth()->user()->getAllForms();
        $folders = $forms->load('folder:id,name')->groupBy('folder.name');

        return view('form.index', compact('folders'));
    }

    /**
     * @return View
     */
    public function create()
    {
        $folders = Folder::all();

        return view('form.create', compact('folders'));
    }

    /**
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
            'user_id' => auth()->id()
        ]);
    }

    /**
     * @param Form $form
     * @return View
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
     * @param Form $form
     * @return View
     * @throws AuthorizationException
     */
    public function edit(Form $form)
    {
        $this->authorize('update', $form);

        $folders = Folder::all();

        $questions = $form->getAnswerableQuestions(['id', 'type', 'title']);

        $emailQuestions = $questions->where('type', 'email')->values();
        $textQuestions = $questions->where('type', 'text')->values();

        return view('form.edit', compact('form', 'folders', 'emailQuestions', 'textQuestions'));
    }

    /**
     * @param FormRequest $request
     * @param Form        $form
     * @return Form
     * @throws AuthorizationException
     */
    public function update(FormRequest $request, Form $form)
    {
        $this->authorize('update', $form);

        if (!CanSetResponseEmailField::isSatisfiedBy($request->input('response_email_field'), $form)) {
            return response()->json(['error' => 'Question for the response email field not valid'], 422);
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
     * @param Form $form
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Form $form)
    {
        $this->authorize('update', $form);

        $form->delete();

        return response()->json(['success' => 'Form deleted']);
    }
}
