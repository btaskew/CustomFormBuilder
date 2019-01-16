<style>
    .email {
        font-family: sans-serif;
        font-size: small;
    }
</style>

<div class="email">
    <h2>Response recorded</h2>

    <p>
        A response was recorded for your form <b>{{ $form->title }}</b>
    </p>
    <p>
        Recorded at: {{ $response['created_at'] }}
    </p>
    <br />

    @foreach($response['answers'] as $question => $answer)
        <b>{{ $question }}</b>
        <br />
        {{ $answer }}
        <br />
        <br />
    @endforeach
</div>