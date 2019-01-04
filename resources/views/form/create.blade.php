@extends('layouts.app')

@section('content')
        <h3>Create a new form</h3>

        <form method="POST" action="/forms" class="mt-3">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
@endsection