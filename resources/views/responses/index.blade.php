@extends('layouts.form')

@section('form_content')
    @if (count($responses) < 1)
        <div class="alert alert-info animated mt" role="alert">
            No responses found
        </div>
    @else

        <a class="btn btn-primary float-right mb-3" href="/forms/{{ $form->id }}/responses/export" role="button">
            Export as spreadsheet
        </a>

        @include('responses._responseTable')

        <div class="d-flex justify-content-center">
            {{ $paginatedResponses }}
        </div>

    @endif
@endsection