@extends('layouts.app')

@section('content')
    @include('navbars.folder')

    <folder-list :folders="{{ json_encode($folders->items()) }}"></folder-list>

    <div class="d-flex justify-content-center">
        {{ $folders->links() }}
    </div>
@endsection