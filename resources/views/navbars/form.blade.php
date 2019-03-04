<nav class="navbar navbar-expand-lg navbar-light mb-3 sub-nav">
    <span class="navbar-brand mb-0 h1">Form: {{ $form->title }}</span>

    <ul class="nav">
        @if (auth()->user()->hasAccessTo('edit', $form))
            <li class="nav-item">
                <a class="nav-link {{ setActive('forms/*/edit') }}" href="/forms/{{ $form->id }}/edit">
                    Edit form
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ setActive('forms/*/questions') }}" href="/forms/{{ $form->id }}/questions">
                    Edit questions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ setActive('forms/*/questions/bank') }}"
                   href="/forms/{{ $form->id }}/questions/bank"
                >
                    Add from question bank
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ setActive('forms/*/access') }}" href="/forms/{{ $form->id }}/access">
                    Edit access
                </a>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link {{ setActive('forms/*/preview') }}" href="/forms/{{ $form->id }}/preview">
                Preview form
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ setActive('forms/*/responses') }}" href="/forms/{{ $form->id }}/responses">
                View responses
            </a>
        </li>
    </ul>
</nav>

<hr />