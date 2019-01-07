@extends('layouts.basic')

<div class="custom-form">
    <h3>{{ $form->getName() }}</h3>

    @if (!is_null($description))
        <p>{{ $description }}</p>
    @endif

    {!! form($form) !!}
</div>