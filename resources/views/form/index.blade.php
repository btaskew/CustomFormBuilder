@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between">
        <h3>My forms</h3>
        <a href="/forms/create">Add form</a>
    </div>

    <form-list :forms="{{ $forms }}"></form-list>
@endsection