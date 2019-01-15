@extends('layouts.form')

@section('form_content')
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col" class="table-active">Response ID</th>
            @foreach ($form->questions as $question)
                <th scope="col">{{ $question->title }}</th>
            @endforeach
            <th scope="col">Response recorded</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($form->responses as $response)
            <tr>
                <th scope="row" class="table-active">{{ $response->id }}</th>
                @foreach ($response->response as $answer)
                <td>{{ $answer }}</td>
                @endforeach
                <td>{{ $response->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection