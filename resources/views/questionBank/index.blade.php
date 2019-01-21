@extends('layouts.form')

@section('form_content')
    <question-bank :questions="{{ json_encode($questions->items()) }}" :form-id="{{ $form->id }}"></question-bank>

    <div class="d-flex justify-content-center">
        {{ $questions->links() }}
    </div>
@endsection