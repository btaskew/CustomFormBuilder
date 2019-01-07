@extends('layouts.form')

@section('form_content')
    <h3>Create form question</h3>

    <div class="mt-4">
        <question-form :form-id={{ $form->id }}></question-form>
    </div>
@endsection