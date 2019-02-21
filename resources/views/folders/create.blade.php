@extends('layouts.app')

@section('content')
    @include('navbars.folder')

    <h3>Create new folder</h3>

    <create-folder-form></create-folder-form>
@endsection