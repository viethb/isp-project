<div class="user-links-container">

    <a href="{{ route('root') }}">Easy Kanban</a>

    @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit">
                Logout
            </button>
        </form>
    @endauth
</div>
