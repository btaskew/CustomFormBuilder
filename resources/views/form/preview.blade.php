@extends('layouts.form')

@section('form_content')
    <div class="custom-form">
        <h3>{{ $formView->getName() }}</h3>

        @if (!is_null($description))
            <p>{{ $description }}</p>
        @endif

        {!! form($formView) !!}
    </div>
@endsection