{{-- selbst erstellter Code --}}
<x-guest-layout>
<div class="welcome-container">

    @include('header-links')

    <h1>Hallo, {{Auth::user()->name}}!</h1>
    <button class="button-small" onclick="showOverlayContainer('addBoardForm')">Neues Board</button>

    {{-- Formular zum Hinzufügen eines neuen Boards, nur sichtbar, wenn der "Neues Board"-Button geklicked wurde --}}
    @include('forms.add-board')

    <div class="board-link-container">
        @foreach(Auth::user()->boards()->orderBy('created_at')->get() as $board)
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
