@extends('layouts.app')

@section('content')

    @include('navbars.form')

    <h3>Create form question</h3>

    <div class="mt-4">
        <form-question-form :form-id={{ $form->id }}></form-question-form>
    </div>
@endsection