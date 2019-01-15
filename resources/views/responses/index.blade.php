@extends('layouts.form')

@section('form_content')
    <a class="btn btn-primary float-right mb-3" href="/forms/{{ $form->id }}/responses/export" role="button">
        Export as spreadsheet
    </a>

    @include('responses._responseTable')
@endsection