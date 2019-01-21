@extends('layouts.form')

@section('form_content')
    <question-bank :questions="{{ json_encode($questions->items()) }}" :form-id="{{ $form->id }}">
        <div class="d-flex justify-content-center" slot="pagination">
            {{ $questions->links() }}
        </div>
    </question-bank>
@endsection