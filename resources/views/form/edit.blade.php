@extends('layouts.app')

@section('content')
    @include('navbars.form')

    <h3>Edit form details</h3>

    <edit-form :form-data="{{ $form }}"></edit-form>
@endsection