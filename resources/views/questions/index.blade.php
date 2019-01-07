@extends('layouts.form')

@section('form_content')
    <div class="d-flex justify-content-between">
        <h3>Edit form questions</h3>
        <a href="/forms/{{ $form->id }}/questions/create">Add question</a>
    </div>

    <div class="mt-4">
        <question-list :questions="{{ $questions }}" :form-id="{{ $form->id }}"></question-list>
    </div>
@endsection