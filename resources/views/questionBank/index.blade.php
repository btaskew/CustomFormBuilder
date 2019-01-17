@extends('layouts.form')

@section('form_content')
    <div class="d-flex justify-content-between">
        <h3>Add questions from question bank</h3>
    </div>

    <question-bank :questions="{{ $questions }}" :form-id="{{ $form->id }}"></question-bank>
@endsection