<div class="user-links-container">

    <a href="{{ route('root') }}">Easy Kanban</a>

{{--    @if(Request::is('board/*'))--}}
{{--        @auth--}}
{{--            <a href="{{ url('/welcome') }}" class="link-secondary">Zurück zu meinen Boards</a>--}}

{{--        @endauth--}}
{{--        @guest--}}
{{--            <a href="{{ url('/') }}" class="link-secondary">Zu einem anderen Board</a>--}}
{{--        @endguest--}}
{{--    @endif--}}

    @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit">
                Logout
            </button>
        </form>
    @endauth
</div>
