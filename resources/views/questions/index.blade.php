@extends('layouts.form')

@section('form_content')
    <h3>Edit form questions</h3>

    <div class="mt-4">
        <question-list :questions="{{ $questions }}"></question-list>
    </div>
@endsection