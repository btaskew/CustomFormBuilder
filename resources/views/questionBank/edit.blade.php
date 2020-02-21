@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h3>Edit question bank question</h3>

        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <a href="{{ url()->previous() }}" class="btn btn-secondary" role="button">Back</a>
            </div>
        </div>
    </div>

    <div class="mt-4">
        @if ($question->in_question_bank == false || $question->form_id != 0)
            <div class="alert alert-danger mt" role="alert">
                Error: Can't edit a non question bank question via this form. Please go to the question's form to
                edit
            </div>
        @else
            <question-bank-form :question="{{ json_encode($question) }}"></question-bank-form>
        @endif
    </div>
@endsection
