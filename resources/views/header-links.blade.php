<div class="user-links-container">

        <a href="{{ route('root') }}">Easy Kanban</a>
{{--    <a href="{{ route('logout') }}">Logout</a>--}}

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit">
            Logout
        </button>
    </form>
</div>
