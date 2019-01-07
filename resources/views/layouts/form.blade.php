@extends('layouts.app')

@section('content')
    <nav class="navbar navbar-expand-lg navbar-light mb-3" id="form-nav">
        <span class="navbar-brand mb-0 h1">Form: {{ $form->title }}</span>

        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="/forms/{{ $form->id }}/edit">Edit form</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/forms/{{ $form->id }}/questions">Edit questions</a>
            </li>
        </ul>
    </nav>

    <hr />

    @yield('form_content')

@endsection