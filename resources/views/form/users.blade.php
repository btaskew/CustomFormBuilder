@extends('layouts.app')

@section('content')
    @include('navbars.form')

    <form-access-manager :form-users="{{ json_encode($users) }}" :form-id="{{ $form->id }}"></form-access-manager>
@endsection