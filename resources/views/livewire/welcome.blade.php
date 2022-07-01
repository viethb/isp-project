<div class="welcome-container">
<script src="{{ asset('js/kanban.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/kanban.css') }}" >
    @include('header-links')

    <h1>Hallo, {{Auth::user()->name}}!</h1>
    <button onclick="showOverlayContainer('addBoardForm')">Neues Board</button>

    {{-- Formular zum Hinzufügen eines neuen Boards, nur sichtbar, wenn der "Neues Board"-Button geklicked wird --}}
    <div id="addBoardForm" class="overlay-container">
        <div class="overlay">
            <form method="POST" action="{{route('addBoard')}}">
                @csrf

                <label for="title">Titel: </label>
                <input type="text" name="title" required>
                <br>
                <label for="description">Beschreibung: </label>
                <input type="text" name="description">
                <br>

                <button type="reset" onclick="hideOverlayContainer('addBoardForm')">
                    Abbrechen
                </button>
                <button type="submit">
                    Speichern
                </button>
            </form>
        </div>
    </div>
    <div class="board-link-container">
        @foreach(Auth::user()->boards()->orderBy('title')->get() as $board)
            <a href="{{ route('showBoard', ['key' => $board->key]) }}">
                <button class="board-link-button">
                    <div>{{ $board->title }}</div>
                    <div>{{ $board->description }}</div>
                </button>
            </a>
        @endforeach
    </div>

</div>
