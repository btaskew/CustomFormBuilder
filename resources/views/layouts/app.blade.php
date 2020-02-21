@extends('layouts.site')

@section('left-links')
    <li class="nav-item">
        <a class="nav-link main-nav-link" href="/forms">My forms</a>
    </li>
    <li class="nav-item">
        <a class="nav-link main-nav-link" href="/forms/create">Create a form</a>
    </li>
@endsection

@section('right-links')
    @auth
        @if (auth()->user()->hasRole('admin'))
            <li class="nav-item">
                <a class="nav-link main-nav-link" href="/admin">Admin site</a>
            </li>
        @endif
    @endauth
@endsection
