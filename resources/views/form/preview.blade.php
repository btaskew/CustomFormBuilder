@extends('layouts.form')

@section('form_content')
    <div>
        <p>This view is for testing only - submitting the form will not store any data as a response.</p>
        @if ($form->active)
            <p>This form is currently <b>active</b></p>
        @else
            <p>This form is currently <b>inactive</b></p>
        @endif
    </div>

    <div class="custom-form border border-secondary rounded p-3">
        <h3>{{ $formView->getName() }}</h3>

        @if (!is_null($description))
            <p>{{ $description }}</p>
        @endif

        {!! form($formView) !!}
    </div>
@endsection