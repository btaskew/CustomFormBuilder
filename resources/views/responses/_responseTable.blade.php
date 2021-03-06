<table class="table table-responsive table-striped table-sm">
    <thead>
    <tr>
        <th scope="col" class="table-active p-2 align-text-top">Response ID</th>
        @foreach ($questions as $question)
            <th scope="col" class="p-2 align-text-top">{{ $question->title }}</th>
        @endforeach
        <th scope="col" class="p-2 align-text-top">Response recorded</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($responses as $response)
        <tr>
            <th scope="row" class="table-active">{{ $response->getId() }}</th>
            @foreach ($response->getAnswers() as $answer)
                <td>{{ $answer }}</td>
            @endforeach
            <td>{{ $response->getTimeRecorded() }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
