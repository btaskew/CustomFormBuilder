@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Folders</h1>

        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <a href="/admin/folders/create" class="btn btn-primary" role="button">Add new folder</a>
            </div>
        </div>

    </div>

    <folder-list :folders="{{ json_encode($folders->items()) }}"></folder-list>

    <div class="d-flex justify-content-center">
        {{ $folders->links() }}
    </div>
@endsection
