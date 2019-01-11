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

    <form-builder :form="{{ json_encode($form->getAttributes()) }}" :questions="{{ json_encode($questions) }}">
    </form-builder>
@endsection