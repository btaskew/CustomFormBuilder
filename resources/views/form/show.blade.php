@extends('layouts.basic')

@section('content')
    <form-builder :form="{{ json_encode($form) }}" :questions="{{ json_encode($questions) }}"></form-builder>
@endsection()