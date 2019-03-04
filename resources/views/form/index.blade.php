@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between">
        <h3>My forms</h3>
    </div>

    <forms-by-folder :folders="{{ $folders }}"></forms-by-folder>
@endsection