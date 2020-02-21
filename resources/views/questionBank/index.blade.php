@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h3>Question bank</h3>

        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <a href="question-bank/create" class="btn btn-primary" role="button">Add question</a>
            </div>
        </div>

    </div>

    <manage-question-bank :questions="{{ json_encode($questions->items()) }}">
        <div class="d-flex justify-content-center" slot="pagination">
            {{ $questions->links() }}
        </div>
    </manage-question-bank>
@endsection
