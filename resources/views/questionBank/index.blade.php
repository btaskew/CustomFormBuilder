@extends('layouts.form')

@section('form_content')
    <question-bank :questions="{{ $questions }}" :form-id="{{ $form->id }}"></question-bank>
@endsection