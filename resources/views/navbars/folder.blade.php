<nav class="navbar navbar-expand-lg navbar-light mb-3" id="form-nav">
    <span class="navbar-brand mb-0 h1">Manage folders</span>

    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link {{ setActive('folders') }}" href="/folders">
                View folders
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ setActive('folders/create') }}" href="/folders/create">
                Add new folder
            </a>
        </li>
    </ul>
</nav>

<hr />