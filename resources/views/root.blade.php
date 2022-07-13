<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Easy Kanban</title>

        <!-- Tab-Icon -->
        <link rel="icon" href="{{ asset('images/green-smiley.png') }}">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" type="text/css" href="{{ asset('css/kanban.css') }}" >
    </head>
    <body>
        <div class="start-page">
            @if (Route::has('login'))

                <div class="user-links-container">
                    <div></div> <!-- leeres Div aus Styling-Gründen notwendig -->
                    <div>
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">
                                Logout   |
                            </button>
                        </form>
                        <a href="{{ url('/welcome') }}">   Meine Boards</a>
                        @else

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm">Registrieren   </a>
                        @endif
                        <a href="{{ route('login') }}" class="text-sm">|   Login</a>
                    @endauth
                    </div>
                </div>
            @endif
            <h1>Easy Kanban</h1>
                <form method="GET" action="{{route('showBoard')}}">
                    @csrf
                    <input type="text" name="board-key" placeholder="Board-Code eingeben" maxlength="16" required>

                    <button type="submit" class="button-large">
                        Board besuchen
                    </button>
                </form>
        </div>
    </body>
</html>
