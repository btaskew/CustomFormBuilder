@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h3>Create a new form</h3></div>
                <br />

                <div class="panel-body">
                    <form method="POST" action="/forms">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection