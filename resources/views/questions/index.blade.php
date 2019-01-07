@extends('layouts.app')

@section('content')
    <h3>{{ $form_title }} - Questions</h3>

    <div class="mt-4">
        @foreach ($questions as $question)
            <edit-question :question="{{ $question }}"></edit-question>
        @endforeach
    </div>
@endsection