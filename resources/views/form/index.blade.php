@extends('layouts.app')

@section('content')
    <h3>My forms</h3>

    <div class="mt-3">
        <ul class="list-group">
            @foreach ($forms as $form)
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ $form->title }}</span>
                    <a href="/forms/{{ $form->id }}/edit">Edit</a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection