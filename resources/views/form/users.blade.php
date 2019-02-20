@extends('layouts.form')

@section('form_content')
    <h3>Edit form access</h3>

    <form-access-list :users="{{ json_encode($users) }}" :form-id="{{ $form->id }}"></form-access-list>
@endsection