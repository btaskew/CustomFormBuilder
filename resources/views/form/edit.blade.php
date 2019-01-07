@extends('layouts.form')

@section('form_content')
    <h3>Edit form details</h3>

    <edit-form :form-data="{{ $form }}"></edit-form>
@endsection