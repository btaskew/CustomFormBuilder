<?php

namespace App\Http\Controllers;

use App\Form;
use App\Mappers\FormMapper;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\Facades\FormBuilder;

class FormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('form.index', ['forms' => auth()->user()->forms]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('form.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $form = Form::create([
            'title' => $request->input('title'),
            'user_id' => auth()->user()->id
        ]);

        return redirect('/forms/' . $form->id . '/edit')->with('flash', 'New form created');
    }

    /**
     * Display the specified resource.
     *
     * @param Form $form
     * @return \Illuminate\View\View
     */
    public function show(Form $form)
    {
        return view('form.show', [
            'form' => $form->build(),
            'description' => $form->description
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Form $form
     * @return \Illuminate\View\View
     */
    public function edit(Form $form)
    {
        return view('form.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Form                      $form
     * @return Form
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Form $form)
    {
        $this->authorize('update', $form);

        $form->update($request->validate([
            'title' => 'string|required',
            'description' => 'string|nullable'
        ]));

        return $form;
    }

    /**
     * Remove the specified resource from storage.
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
