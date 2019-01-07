<?php

namespace App\Http\Controllers;

use App\Form;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\Facades\FormBuilder;

class FormController extends Controller
{
    /**
     * Create a new ThreadsController instance.
     */
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
        $forms = auth()->user()->forms;

        if (request()->wantsJson()) {
            return $forms;
        }

        return view('form.index', compact('forms'));
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
        $questions = $form->questions;
        $formView = FormBuilder::plain(['name' => $form->title]);

        foreach ($questions as $question) {
            $formView->add($question->id, $question->type, [
                'label' => $question->title
            ]);
        }

        return view('form.show', [
            'form' => $formView,
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
     */
    public function update(Request $request, Form $form)
    {
        $form->update($request->validate([
            'title' => 'string|required',
            'description' => 'string'
        ]));

        return $form;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
