@extends('layouts.app')

@section('content')

    @include('navbars.form')

    <h3>Create form question</h3>

    <div class="mt-4">
        <question-form :form-id={{ $form->id }}></question-form>
    </div>
@endsection