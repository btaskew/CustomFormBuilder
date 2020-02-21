@extends('layouts.site')

@section('left-links')
    <li class="nav-item">
        <a class="nav-link main-nav-link" href="/admin">
            <b>ADMIN</b>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link main-nav-link" href="/admin/folders">
            Manage folders
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link main-nav-link" href="/admin/question-bank">
            Manage question bank
        </a>
    </li>
@endsection

@section('right-links')
    <li class="nav-item">
        <a class="nav-link main-nav-link" href="/forms">Normal site</a>
    </li>
@endsection
