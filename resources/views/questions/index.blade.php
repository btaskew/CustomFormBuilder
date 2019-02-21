@extends('layouts.app')

@section('content')

    @include('navbars.form')
    <div class="d-flex justify-content-between">
        <h3>Edit form questions</h3>
        <a class="btn btn-primary" href="/forms/{{ $form->id }}/questions/create">Add question</a>
    </div>

    <div class="mt-4">
        <question-list :questions="{{ $questions }}" :form-id="{{ $form->id }}"></question-list>
    </div>
@endsection