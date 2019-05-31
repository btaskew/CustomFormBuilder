@extends('layouts.app')

@section('content')
    @include('navbars.form')

    <h3>Edit form details</h3>

    <edit-form
            :form-data="{{ $form }}"
            :folders="{{ json_encode($folders) }}"
            :email-questions="{{ json_encode($emailQuestions) }}"
            :text-questions="{{ json_encode($textQuestions) }}"
    ></edit-form>
@endsection
