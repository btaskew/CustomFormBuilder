@extends('layouts.app')

@section('content')
    @include('navbars.form')

    <h3>Edit form details</h3>

    <p>
        When active your form can be accessed via:
        <a href="{{ url("forms/$form->id") }}">{{ url("forms/$form->id") }}</a>
    </p>

    <edit-form
            :form-data="{{ $form }}"
            :folders="{{ json_encode($folders) }}"
            :email-questions="{{ json_encode($emailQuestions) }}"
            :text-questions="{{ json_encode($textQuestions) }}"
    ></edit-form>
@endsection
