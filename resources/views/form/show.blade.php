@extends('layouts.basic')

@section('content')
    <div>
        <h3>{{ $form->title }}</h3>
        <div class="mt-3 mb-3">{!! $form->description !!}</div>
        <form-builder :form="{{ json_encode($form) }}" :questions="{{ json_encode($questions) }}"></form-builder>
    </div>
@endsection()