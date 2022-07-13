<x-guest-layout>
<div class="welcome-container">
<script src="{{ asset('js/kanban.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/kanban.css') }}" >
    @include('header-links')

    <h1>Hallo, {{Auth::user()->name}}!</h1>
    <button class="button-small" onclick="showOverlayContainer('addBoardForm')">Neues Board</button>

    {{-- Formular zum Hinzufügen eines neuen Boards, nur sichtbar, wenn der "Neues Board"-Button geklicked wurde --}}
    <div id="addBoardForm" class="overlay-container">
        <div class="overlay">
            <form method="POST" action="{{route('addBoard')}}">
                @csrf
                <div class="overlay-section">

                    <h3>Neues Board</h3>
                    <label for="title">Titel</label>
                    <input type="text" name="title" maxlength="255" required>

                    <label for="description">Beschreibung</label>
                    <textarea name="description" rows="5" maxlength="1000"></textarea>

                    <div class="button-container">
                        <button type="reset" onclick="hideOverlayContainer('addBoardForm')" class="button-secondary">
                            Abbrechen
                        </button>
                        <button type="submit" class="button-small">
                            Speichern
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="board-link-container">
        @foreach(Auth::user()->boards()->orderBy('title')->get() as $board)
            <a href="{{ route('showBoard', ['key' => $board->key]) }}">
                <button class="board-link-button" title="{{ $board->title }}">
                    <div><h4>{{ $board->title }}</h4></div>
                    <div>{{ $board->description }}</div>
                </button>
            </a>
        @endforeach
    </div>

</div>
</x-guest-layout>
