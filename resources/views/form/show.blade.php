@extends('layouts.basic')

@section('content')
    <form-builder :form="{{ json_encode($form) }}"></form-builder>
@endsection()