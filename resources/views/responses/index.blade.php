@extends('layouts.app')

@section('content')

    @include('navbars.form')
    
    @if (count($responses) < 1)
        <div class="alert alert-info mt" role="alert">
            No responses found
        </div>
    @else

        <div class="d-flex justify-content-between">
            <span>Response count: {{ $pagination->total() }}</span>

            <a class="btn btn-primary mb-3" href="/forms/{{ $form->id }}/responses/export" role="button">
                Export as spreadsheet
            </a>
        </div>

        @include('responses._responseTable')

        <div class="d-flex justify-content-center">
            {{ $pagination->links() }}
        </div>

    @endif
@endsection