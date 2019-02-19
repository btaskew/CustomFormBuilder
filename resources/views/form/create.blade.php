@extends('layouts.app')

@section('content')
    <h3>Create a new form</h3>

    <edit-form :folders="{{ json_encode($folders) }}"></edit-form>
@endsection