@extends('layouts.app')

@section('content')
    @include('navbars.form')

    <div>
        <p>This view is for testing only - submitting the form will not store any data as a response.</p>
        @if ($form->active)
            <p>This form is currently <b>active</b></p>
            <p>
                Your form can be accessed via: <a href="{{ url("forms/$form->id") }}">{{ url("forms/$form->id") }}</a>
            </p>
        @else
            <p>This form is currently <b>inactive</b></p>
        @endif
    </div>

    <div class="p-3 border rounded">
        <h3>{{ $form['title'] }}</h3>
        <div class="mt-3 mb-2">{!! $form['description'] !!}</div>
        <hr />
        <form-builder
                :form="{{ json_encode($form->getAttributes()) }}"
                :questions="{{ json_encode($questions) }}"
                :is-preview="true"
        >
        </form-builder>
    </div>
@endsection
