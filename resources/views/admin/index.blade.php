@extends('layouts.admin')

@section('content')
    <h1>Admin dashboard</h1>

    <br />

    <div class="list-group">
        <a href="/admin/folders" class="list-group-item list-group-item-action">Manage folders</a>
        <a href="/admin/question-bank" class="list-group-item list-group-item-action">Manage question bank</a>
    </div>
@endsection
