@extends('layouts.form')

@section('form_content')


    <form-access-manager :form-users="{{ json_encode($users) }}" :form-id="{{ $form->id }}"></form-access-manager>
@endsection