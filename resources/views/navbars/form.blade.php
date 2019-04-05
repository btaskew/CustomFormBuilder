<nav class="navbar navbar-expand-lg navbar-light mb-3 sub-nav">
    <span class="navbar-brand mb-0 h1">Form: {{ str_limit($form->title, 25) }}</span>

    <ul class="nav">
        @if (auth()->user()->hasAccessTo('edit', $form))
            <li class="nav-item">
                <a class="nav-link {{ setActiveLink('forms/*/edit') }}" href="/forms/{{ $form->id }}/edit">
                    Edit form
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ setActiveLink('forms/*/questions') }}" href="/forms/{{ $form->id }}/questions">
                    Edit questions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ setActiveLink('forms/*/question-bank') }}"
                   href="/forms/{{ $form->id }}/question-bank"
                >
                    Add from question bank
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ setActiveLink('forms/*/access') }}" href="/forms/{{ $form->id }}/access">
                    Edit access
                </a>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link {{ setActiveLink('forms/*/preview') }}" href="/forms/{{ $form->id }}/preview">
                Preview form
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ setActiveLink('forms/*/responses') }}" href="/forms/{{ $form->id }}/responses">
                View responses
            </a>
        </li>
    </ul>
</nav>

<hr />