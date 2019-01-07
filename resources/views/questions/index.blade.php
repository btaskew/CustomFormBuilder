@extends('layouts.app')

@section('content')
    <h3>{{ $form_title }} - Questions</h3>

    <div class="mt-4">
        <question-list :questions="{{ $questions }}"></question-list>
    </div>
@endsection