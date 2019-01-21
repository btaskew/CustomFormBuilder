@extends('layouts.app')

@section('content')
    <nav class="navbar navbar-expand-lg navbar-light mb-3" id="form-nav">
        <span class="navbar-brand mb-0 h1">Form: {{ $form->title }}</span>

        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link {{ setActive('forms/*/edit') }}" href="/forms/{{ $form->id }}/edit">
                    Edit form
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ setActive('forms/*/questions') }}" href="/forms/{{ $form->id }}/questions">
                    Edit questions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ setActive('forms/*/questions/bank') }}" href="/forms/{{ $form->id }}/questions/bank">
                    Add from question bank
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ setActive('forms/*/preview') }}" href="/forms/{{ $form->id }}/preview">
                    Preview form
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ setActive('forms/*/responses') }}" href="/forms/{{ $form->id }}/responses">
                    View responses
                </a>
            </li>
        </ul>
    </nav>

    <hr />

    @yield('form_content')

@endsection